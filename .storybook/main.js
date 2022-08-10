const stories = [
  '../src/stories/**/*.@(js|tsx|mdx)'
].filter( Boolean );

const customEnvVariables = {};

module.exports = {
  core: {
    builder: 'webpack5',
  },
  stories,
  addons: [
    {
      name: '@storybook/addon-docs',
      options: { configureJSX: true },
    },
    '@storybook/addon-controls',
    '@storybook/addon-knobs', // Deprecated, new stories should use addon-controls.
    '@storybook/addon-storysource',
    '@storybook/addon-viewport',
    '@storybook/addon-a11y',
    '@storybook/addon-toolbars',
    '@storybook/addon-actions',
  ],
  features: {
    babelModeV7: true,
    emotionAlias: false,
  }
};
