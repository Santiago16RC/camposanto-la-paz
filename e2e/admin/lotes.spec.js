import { test, expect } from '@playwright/test';

test.describe('Panel · Lotes', () => {
    test('la lista de lotes muestra los datos existentes', async ({ page }) => {
        await page.goto('/admin/lotes');

        await expect(page.getByRole('heading', { name: 'Lotes' })).toBeVisible();
        await expect(page.getByText('A-101')).toBeVisible();
    });

    test('permite crear un lote nuevo', async ({ page }) => {
        const codigo = `TEST-${Date.now()}`;

        await page.goto('/admin/lotes/create');
        await page.getByLabel('Código').fill(codigo);
        await page.getByLabel('Sección').fill('Sección de prueba');
        await page.getByLabel('Estado').selectOption('disponible');
        await page.getByRole('button', { name: 'Crear', exact: true }).click();

        // Filament redirige a la edición del registro recién creado, no a la lista.
        await expect(page).toHaveURL(/\/admin\/lotes\/\d+\/edit$/, { timeout: 20000 });

        // Con muchos lotes acumulados, el nuevo puede quedar en otra página de la lista —
        // se busca por código para no depender de la paginación.
        await page.goto('/admin/lotes');
        await page.getByRole('searchbox').fill(codigo);
        // Se acota a la tabla porque el buscador también muestra el texto en una "burbuja" de filtro activo.
        await expect(page.locator('table').getByText(codigo)).toBeVisible({ timeout: 15000 });
    });

    test('permite editar el estado de un lote existente', async ({ page }) => {
        await page.goto('/admin/lotes');
        await page.getByRole('searchbox').fill('A-102');

        await page.getByRole('row', { name: /A-102/ }).getByRole('link', { name: 'Editar' }).click();
        await expect(page).toHaveURL(/\/admin\/lotes\/\d+\/edit$/);
        await page.getByLabel('Estado').selectOption('ocupado');
        await page.getByRole('button', { name: 'Guardar cambios' }).click();
        await page.waitForLoadState('networkidle');

        await page.goto('/admin/lotes');
        await page.getByRole('searchbox').fill('A-102');
        await expect(page.getByRole('row', { name: /A-102/ })).toContainText('ocupado');
    });
});
