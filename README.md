# Pro2Store

Pro2Store is an Ecommerce Extension for YOOtheme Pro

## Installation

- Pull the repo into a pre-installed Joomla installation.
- Open in (ideally) PHPStorm
- Make sure you have Node and NPM installed and open the PHPStorm terminal and run:

```bash
npm install
```

- NPM install creates a folder called 'node_modules' in your installation - it includes all the JavaScript needed to run the builds.
- In your Joomla backend, use the "Discover" function to install the Pro2Store system

## Vue Development

- The Pro2Store backend uses Vue3 for the page interactivity. 
- Each page in the backend has a corresponding Vue.js file located in "/vuefiles"
- These are compiled and minified into the corresponding "media/com_protostore/js/vue" directory.
- The "bundlevue.js" script needs to be run on each change to the js in the "/vuefiles" directory.
- So make sure to run the "vuedev" npm script while developing. This watches changes and automatically compiles the code:

```bash
npm run-script vuedev
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.


## License
GNU GENERAL PUBLIC LICENSE Version 2
