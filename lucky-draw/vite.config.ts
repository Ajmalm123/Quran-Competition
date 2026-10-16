import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";
import fs from "fs";

export default defineConfig({
  plugins: [
    react(),
    {
      name: "remove-html",
      closeBundle() {
        const htmlPath = path.resolve(__dirname, "../public/lucky-draw-assets/index.html");
        if (fs.existsSync(htmlPath)) {
          fs.unlinkSync(htmlPath);
        }
      },
    },
  ],
  base: "/",
  build: {
    outDir: path.resolve(__dirname, "../public/lucky-draw-assets"),
    emptyOutDir: false,
    rollupOptions: {
      input: path.resolve(__dirname, "index.html"),
      output: {
        entryFileNames: "lucky-draw.js",
        chunkFileNames: "assets/[name].js",
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith(".css")) {
            return "lucky-draw.css";
          }
          return "assets/[name].[ext]";
        },
      },
    },
  },
});
