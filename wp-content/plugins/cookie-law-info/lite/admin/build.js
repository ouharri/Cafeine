const fs = require('fs-extra')
const path = require("path");
const source = path.resolve(
    __dirname,
    '../..'
);
const target = path.resolve(
    __dirname,
    '../../../build/cookie-law-info'
);

async function build() {
    await copyFiles();
    await deleteFiles();
}

async function copyFiles() {
    try {
        await fs.copy(source, target);
    } catch (err) {
        console.error(err)
    }
}
async function deleteFiles() {
    try {
        await fs.remove(`${target}/lite/admin/node_modules`)
        await fs.remove(`${target}/lite/admin/src`)
        await fs.remove(`${target}/.git`)
        await fs.remove(`${target}/.gitignore`)
        console.log('success!')
    } catch (err) {
        console.error(err)
    }
}
build();