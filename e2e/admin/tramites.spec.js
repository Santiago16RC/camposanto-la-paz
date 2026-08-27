import { test, expect } from '@playwright/test';

function today() {
    return new Date().toISOString().slice(0, 10);
}

test.describe('Panel · Trámites', () => {
    test('la lista de trámites se muestra', async ({ page }) => {
        await page.goto('/admin/tramites');

        await expect(page.getByRole('heading', { name: 'Trámites' })).toBeVisible();
    });

    test('permite crear un trámite nuevo', async ({ page }) => {
        const solicitante = `Solicitante ${Date.now()}`;

        await page.goto('/admin/tramites/create');
        await page.getByLabel('Tipo').selectOption('mantenimiento');
        await page.getByLabel('Estado').selectOption('pendiente');
        await page.getByLabel('Solicitante').fill(solicitante);
        await page.getByLabel('Fecha de solicitud').fill(today());
        await page.getByRole('button', { name: 'Crear', exact: true }).click();

        // Filament redirige a la edición del registro recién creado, no a la lista.
        await expect(page).toHaveURL(/\/admin\/tramites\/\d+\/edit$/, { timeout: 20000 });

        // Con muchos trámites acumulados, el nuevo puede quedar en otra página de la lista —
        // se busca por solicitante para no depender de la paginación.
        await page.goto('/admin/tramites');
        await page.getByRole('searchbox').fill(solicitante);
        // Se acota a la tabla porque el buscador también muestra el texto en una "burbuja" de filtro activo.
        await expect(page.locator('table').getByText(solicitante)).toBeVisible({ timeout: 15000 });
    });

    test('permite cambiar el estado del trámite existente', async ({ page }) => {
        await page.goto('/admin/tramites');
        await page.getByRole('searchbox').fill('Familia Rodríguez');

        await page.getByRole('row', { name: /Familia Rodríguez/ }).getByRole('link', { name: 'Editar' }).click();
        await expect(page).toHaveURL(/\/admin\/tramites\/\d+\/edit$/);
        await page.getByLabel('Estado').selectOption('completado');
        await page.getByRole('button', { name: 'Guardar cambios' }).click();
        await page.waitForLoadState('networkidle');

        await page.goto('/admin/tramites');
        await page.getByRole('searchbox').fill('Familia Rodríguez');
        await expect(page.getByRole('row', { name: /Familia Rodríguez/ })).toContainText('completado');
    });
});
