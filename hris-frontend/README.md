## HRIS Frontend Nuxt 2

All about this packages

## Dependencies

```bash
# tailwindcss
1. npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init

2. nuxt.config.js
export default {
  build: {
    postcss: {
      postcssOptions: {
        plugins: {
          tailwindcss: {},
          autoprefixer: {},
        },
      },
    },
  }
}

3. In tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./nuxt.config.{js,ts}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

4. main.css
@tailwind base;
@tailwind components;
@tailwind utilities;

5. nuxt.config.js
export default {
  css: [
    '@/assets/css/main.css',
  ],
}

# nuxt auth and axios
npm install --save-exact @nuxtjs/auth-next
npm install @nuxtjs/axios

#vuex
npm install vuex@next --save --legacy-peer-deps
```

## Setup

Make sure to install the dependencies:

```bash
# npm
npm install

# pnpm
pnpm install

# yarn
yarn install
```

## Development Server

Start the development server on `http://localhost:3000`:

```bash
# npm
npm run dev

# pnpm
pnpm run dev

# yarn
yarn dev
```

## Production

Build the application for production:

```bash
# npm
npm run build

# pnpm
pnpm run build

# yarn
yarn build
```

Locally preview production build:

```bash
# npm
npm run preview

# pnpm
pnpm run preview

# yarn
yarn preview
```
