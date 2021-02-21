import 'babel-polyfill'

import './../scss/gutenberg.scss'

import './gutenberg/imports'
import init from './gutenberg/init'
import { getContent, setContent } from './lib/content'
import { registerBlock, registerCategory } from './lib/custom-blocks'

const Gutenberg = {
  blocks : [],
  categories : [],
  init: init,
  initGutenberg: init,
  getContent: getContent,
  setContent: setContent,
  editor: null,
  registerCategory: registerCategory,
  registerBlock: registerBlock,
  beforeBoot : [],
  onBoot : [],
}

window.Gutenberg = Gutenberg

export default Gutenberg