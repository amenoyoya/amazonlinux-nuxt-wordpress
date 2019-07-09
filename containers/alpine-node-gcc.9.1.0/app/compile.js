/**
 * gcc compiler bootstrap
 */
const exec = require('child_process').execSync;

(() => {
  if (process.argv.length-2 === 0) {
    console.log('Please set compiling target');
    return 0;
  }
  const res = exec('gcc "' + process.argv[2] + '.cpp" -lstdc++ -o "' + process.argv[2] + '"');
  console.log(res.toString());
})();
