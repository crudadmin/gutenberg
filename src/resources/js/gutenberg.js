import './../scss/gutenberg.scss'

import './gutenberg/imports'
import init from './gutenberg/init'
import { getContent, setContent } from './lib/content'
import { registerBlock, registerCategory } from './lib/custom-blocks'

const Gutenberg = {
  //Api
  registerCategory: registerCategory,
  registerBlock: registerBlock,
  init: init,
  initGutenberg: init,
  getContent: getContent,
  setContent: setContent,

  //This blocks will be unregister on editor boot
  unregister_blocks : [],
  blocks : [],
  categories : [],

  //Boot callback events
  beforeBoot : [],
  onBoot : [],

  //Mutate configuration with callback functions
  configure : [
    (options) => {
      return options;
    }
  ],

  //Core propertie
  editor: null,
}

// The admin evaluates this bundle more than once per page. Only the first run
// may own window.wp and window.Gutenberg; see the guard in vite.config.mjs.
window.__gutenbergBundle = Gutenberg

window.Gutenberg = Gutenberg

export default Gutenberg