// tests/quote-form.spec.js
const { test, expect } = require('@playwright/test');

// test('Quote form submits successfully', async ({ page }) => {
//     // 1. Go to the page where the shortcode is rendered
//     await page.goto('https://a-mission-for-michael.local/bonza/'); // Change to your actual URL

//     // 2. Fill out the form
//     await page.fill('input[name="wqm_name"]', 'Another One');
//     await page.fill('input[name="wqm_email"]', 'another@example.com');
//     await page.fill('input[name="wqm_service_type"]', 'Test Service');
//     await page.fill('textarea[name="wqm_notes"]', 'Some notes for testing.');

//     // 3. Submit the form
//     await page.click('input[type="submit"][name="wqm_submit"]');

//     // 4. Assert the success message appears
//     await expect(page.locator('.wqm-success')).toHaveText(/Quote submitted successfully!/i);
// });

test('Backend Data Check', async ({ page }) => {
    // login to the admin dashboard
    await page.goto('https://a-mission-for-michael.local/wp-login.php'); // Change to your actual URL
   
     // Fill in credentials and submit
    await page.fill('input[name="log"]', 'adrian.saycon@amfmhealthcare.com');
    await page.fill('input[name="pwd"]', 'Gtzkhclnejlm!2345');
    await Promise.all([
        page.waitForNavigation(), // Wait for navigation after login
        page.click('input[type="submit"]'),
    ]);


    // Wait for the admin dashboard to load
    await page.waitForSelector('#adminmenu');

    // 1. Go to the admin page where quotes are listed
    await page.goto('https://a-mission-for-michael.local/wp-admin/admin.php?page=wqm-quotes'); // Change to your actual URL

    // 2. Check if the quote appears in the list
    const quoteRow = page.locator('tr:has-text("Test User")');
    await expect(quoteRow).toBeVisible();
});
