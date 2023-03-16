const fs = require("fs");

const imagesDir = `${__dirname}/../images`;
const buildDir = `${__dirname}/../build`;

const flags = [
  {
    name: "square",
    inputSize: "1x1",
  },
  {
    name: "shiny",
    inputSize: "4x3",
  },
  {
    name: "circle",
    inputSize: "1x1",
    overlay: `<clipPath id="wg-round-mask"><circle cx="256" cy="256" r="256" fill="white" /></clipPath><g clip-path="url(#wg-round-mask)">$<svg></g>`,
  },
  {
    name: "rectangle_mat",
    inputSize: "4x3",
    overlay: `$<svg><rect width="100%" height="100%" style="fill:rgb(255,255,255,0.2)" />`,
  },
];

if (!fs.existsSync(buildDir)) {
  fs.mkdirSync(buildDir);
}

for (const flag of flags) {
  const outputPath = `${buildDir}/${flag.name}`;

  if (!fs.existsSync(outputPath)) {
    fs.mkdirSync(outputPath);
  }

  ["flag-icon-css", "flags"]
    .map((folder) => {
      const path = `${imagesDir}/${folder}/${flag.inputSize}`;
      return fs.readdirSync(path).map((file) => ({
        file,
        path,
      }));
    })
    .flat()
    .forEach(({ path, file }) => {
      let svg = fs.readFileSync(`${path}/${file}`).toString();

      if (flag.overlay) {
        const regex = /(<svg(?:[^>]+)>)(?<svg>[\s\S]+)(<\/svg>)/i;
        svg = svg.replace(regex, `$1${flag.overlay}$3`);
      }

      fs.writeFileSync(`${outputPath}/${file}`, svg);
    });
}
