const sharp = require("sharp");

sharp("example-image.jpg")
  .resize({ width: 500, height: 450 })
  .toFile("output.jpg");
