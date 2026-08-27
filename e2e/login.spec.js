import { test, expect } from '@playwright/test';

const ADMIN_EMAIL = 'admin@camposantolapaz.test';
const ADMIN_PASSWORD = 'password123';

test.describe('Login', () => {
    test('la página de login se muestra correctamente', async ({ page }) => {
        await page.goto('/login');
        await page.waitForLoadState('networkidle');

        await expect(page).toHaveTitle('Camposanto La Paz');
        await expect(page.getByRole('textbox', { name: 'Correo electrónico' })).toBeVisible();
        await expect(page.getByRole('textbox', { name: 'Contraseña' })).toBeVisible();
        await expect(page.getByRole('button', { name: 'Iniciar sesión' })).toBeVisible();
    });

    test('rechaza credenciales inválidas con un mensaje de error', async ({ page }) => {
        await page.goto('/login');
        await page.waitForLoadState('networkidle');

        await page.getByRole('textbox', { name: 'Correo electrónico' }).fill(ADMIN_EMAIL);
        await page.getByRole('textbox', { name: 'Contraseña' }).fill('contraseña-incorrecta');
        await page.getByRole('button', { name: 'Iniciar sesión' }).click();

        await expect(page.getByText('Estas credenciales no coinciden con nuestros registros.')).toBeVisible();
        await expect(page).toHaveURL(/\/login$/);
    });

    test('exige correo y contraseña', async ({ page }) => {
        await page.goto('/login');
        await page.waitForLoadState('networkidle');

        // Desactiva la validación nativa del navegador (atributo `required`)
        // para poder comprobar los mensajes de validación del servidor.
        await page.evaluate(() => document.querySelector('form')?.setAttribute('novalidate', 'true'));

        await page.getByRole('button', { name: 'Iniciar sesión' }).click();

        await expect(page.getByText('El campo correo electrónico es obligatorio.')).toBeVisible();
        await expect(page.getByText('El campo contraseña es obligatorio.')).toBeVisible();
    });

    test('permite iniciar sesión con credenciales válidas y llegar al panel', async ({ page }) => {
        await page.goto('/login');
        await page.waitForLoadState('networkidle');

        await page.getByRole('textbox', { name: 'Correo electrónico' }).fill(ADMIN_EMAIL);
        await page.getByRole('textbox', { name: 'Contraseña' }).fill(ADMIN_PASSWORD);
        await page.getByRole('button', { name: 'Iniciar sesión' }).click();

        await expect(page).toHaveURL(/\/dashboard$/, { timeout: 45000 });
        await expect(page.getByText('Sesión iniciada correctamente, Administrador.')).toBeVisible();
    });

    test('permite cerrar sesión desde el panel', async ({ page }) => {
        await page.goto('/login');
        await page.waitForLoadState('networkidle');
        await page.getByRole('textbox', { name: 'Correo electrónico' }).fill(ADMIN_EMAIL);
        await page.getByRole('textbox', { name: 'Contraseña' }).fill(ADMIN_PASSWORD);
        await page.getByRole('button', { name: 'Iniciar sesión' }).click();
        await expect(page).toHaveURL(/\/dashboard$/, { timeout: 45000 });

        await page.getByRole('button', { name: 'Administrador' }).click();
        await page.getByText('Cerrar sesión').first().click();

        await expect(page).toHaveURL(/\/$/);
        await page.goto('/dashboard');
        await expect(page).toHaveURL(/\/login$/);
    });
});
