import { test as setup, expect } from '@playwright/test';

const ADMIN_EMAIL = 'admin@camposantolapaz.test';
const ADMIN_PASSWORD = 'password123';

const authFile = 'e2e/.auth/admin.json';

setup('autenticar como administrador en el panel', async ({ page }) => {
    await page.goto('/admin/login');
    await page.waitForLoadState('networkidle');

    await page.getByLabel('Correo electrónico').fill(ADMIN_EMAIL);
    await page.locator('#form\\.password').fill(ADMIN_PASSWORD);
    await page.getByRole('button', { name: 'Entrar' }).click();

    await expect(page).toHaveURL(/\/admin$/, { timeout: 30000 });

    await page.context().storageState({ path: authFile });
});
