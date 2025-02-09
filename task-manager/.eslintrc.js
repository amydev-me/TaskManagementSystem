module.exports = {
    root: true,
    env: {
      browser: true,
      node: true,
      es6: true,  // Ensure ES6 support
    },
    parserOptions: {
      parser: "@babel/eslint-parser",
      requireConfigFile: false, // Disable Babel config file requirement
      sourceType: "module", // Ensure module imports work
      ecmaVersion: 2020, // Use the latest ECMAScript version
    },
    extends: [
      "plugin:vue/essential",
      "eslint:recommended"
    ],
    rules: {
      "no-console": "off",
      "no-debugger": "off",
    }
  };
  