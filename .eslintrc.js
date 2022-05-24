/**
 * Set WordPress presets
 */

const eslintConfig = {
  extends: ["plugin:@wordpress/eslint-plugin/recommended"],
  rules: {
    "padding-line-between-statements": [
      "error",
      {
        blankLine: "always",
        next: ["function", "const", "let", "var"],
        prev: "return",
      },
    ],
    "max-len": ["error", { code: 160, tabWidth: 12 }],
    "prettier/prettier": ["error", { endOfLine: "auto" }],
  },
};

eslintConfig.parserOptions = {
  ecmaVersion: 6,
  env: { es6: true },
  babelOptions: {
    presets: [require.resolve("@wordpress/babel-preset-default")],
  },
};

module.exports = eslintConfig;
