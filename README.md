# Pro2Store

Pro2Store is an Ecommerce Extension for YOOtheme Pro

## Requirements

- Requires at least PHP 7.1 (Aiming for PHP8.1 asap!)
- Joomla 4 (J3 Supported but not actively developed)
- YOOtheme Pro (latest!)

## Installation

- Pull the repo onto your local machine. this will create a folder called "Pro2Store".
- Download Joomla 4: https://downloads.joomla.org/ and unzip.
- Copy the Joomla core files to the Pro2Store folder NOTE: Don't overwrite the files… click "Merge" if promtped
- Install Joomla as usual
- Once installed, click "system >> discover". You should now see a list of all the Pro2Store plugins etc.
- Select them all and click install


### NOTE: Before going to the component, make sure all the plugins are enabled AND you follow the steps below

## To begin Development

- Open in (ideally) PHPStorm
- Make sure you have Node and NPM installed and open the PHPStorm terminal and run:

```bash
npm install
```

- NPM install creates a folder called 'node_modules' in your installation – it includes all the JavaScript needed to run the builds.
- run this command to compile the Vue Javascript:

```bash
node bundlevue.js
```

### Now you can visit the component backend!

## Vue Development

- The Pro2Store backend uses Vue3 for the page interactivity. 
- Each page in the backend has a corresponding Vue.js file located in "/vuefiles"
- These are compiled and minified into the corresponding "media/com_protostore/js/vue" directory.
- The "bundlevue.js" script needs to be run on each change to the js in the "/vuefiles" directory.
- So make sure to run the "vuedev" npm script while developing. This watches changes and automatically compiles the code:

```bash
npm run-script vuedev
```

## Building an installable Joomla Package

- run:
```bash
npm run-script build
```
- this will bundle all the plugins, modules and the main component into a zip file and place it in the 'dist' folder

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## Joomla 4

- This version is to be Joomla 4 compatible. This is a work in progress.

## License
GNU GENERAL PUBLIC LICENSE Version 2
