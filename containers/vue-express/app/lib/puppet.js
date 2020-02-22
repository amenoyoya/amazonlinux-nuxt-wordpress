/**
 * Docker環境でのPuppeteer
 */
const puppeteer = require('puppeteer');

/**
 * puppeteer実行関数
 * @param option: object puppeteer起動オプション
 * @param callback: async (browser, page) => null puppeteer実行関数
 */
const puppet = async (option, callback) => {
  const browser = await puppeteer.launch(
    // Dockerで動かすとき用のオプション
    process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD?
    {
      executablePath: '/usr/bin/chromium-browser',
      args: ['--disable-dev-shm-usage', '--disable-setuid-sandbox', '--no-sandbox'],
      ...option
    }
    : {
      args: ['--disable-dev-shm-usage', '--disable-setuid-sandbox', '--no-sandbox'],
      ...option
    }
  );
  const page = await browser.newPage();
  if (option.viewport !== undefined ) {
    await page.setViewport(option.viewport);
  }
  await callback(browser, page);
  await browser.close();
};

// export default
export default puppet;
