import { test, expect } from '@playwright/test';

test.describe('Panel · Usuarios', () => {
    test('la lista de usuarios se muestra', async ({ page }) => {
        await page.goto('/admin/users');

        await expect(page.getByRole('heading', { name: 'Usuarios' })).toBeVisible();
        await expect(page.getByText('admin@camposantolapaz.test')).toBeVisible();
    });

    test('permite crear un usuario nuevo', async ({ page }) => {
        const email = `usuario-${Date.now()}@camposantolapaz.test`;

        await page.goto('/admin/users/create');
        await page.getByLabel('Nombre').fill('Usuario de prueba');
        await page.getByLabel('Correo electrónico').fill(email);
        await page.locator('#form\\.password').fill('password123');
        await page.getByRole('button', { name: 'Crear', exact: true }).click();

        // Filament redirige a la edición del registro recién creado, no a la lista.
        await expect(page).toHaveURL(/\/admin\/users\/\d+\/edit$/, { timeout: 20000 });

        // Con muchos usuarios acumulados, el nuevo puede quedar en otra página de la lista —
        // se busca por correo para no depender de la paginación.
        await page.goto('/admin/users');
        await page.getByRole('searchbox').fill(email);
        // Se acota a la tabla porque el buscador también muestra el texto en una "burbuja" de filtro activo.
        await expect(page.locator('table').getByText(email)).toBeVisible({ timeout: 15000 });
    });

    test('permite dar permiso de administrador a un usuario', async ({ page }) => {
        const email = `admin-nuevo-${Date.now()}@camposantolapaz.test`;

        await page.goto('/admin/users/create');
        await page.getByLabel('Nombre').fill('Futuro administrador');
        await page.getByLabel('Correo electrónico').fill(email);
        await page.locator('#form\\.password').fill('password123');
        await page.getByRole('button', { name: 'Crear', exact: true }).click();

        // Ya quedamos en la página de edición del usuario recién creado.
        await expect(page).toHaveURL(/\/admin\/users\/\d+\/edit$/, { timeout: 20000 });
        await page.getByLabel('Administrador').click();
        // Confirma que el interruptor ya cambió de estado en el navegador antes de guardar.
        await expect(page.getByLabel('Administrador')).toHaveAttribute('aria-checked', 'true');

        await page.getByRole('button', { name: 'Guardar cambios' }).click();
        // Espera a que termine la petición de Livewire antes de recargar —
        // si se recarga demasiado pronto, el guardado aún puede no haber terminado.
        await page.waitForLoadState('networkidle');

        await page.reload();
        await expect(page.getByLabel('Administrador')).toHaveAttribute('aria-checked', 'true');
    });
});
