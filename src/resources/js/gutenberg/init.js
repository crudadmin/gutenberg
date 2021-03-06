import { editorSettings, overridePost } from './settings'
import { configureAPI } from '../api/api-fetch'
import configureEditor from '../lib/configure-editor'
import { elementReady } from '../lib/element-ready'

const { blocks, data, domReady, editPost, plugins } = window.wp
const { unregisterBlockType, registerBlockType, getBlockType } = blocks

/**
 * Initialize the Gutenberg editor
 * @param {string} target the element ID to render the gutenberg editor in
 */
export default async function init(target, options = {}) {
  configureAPI(options);

  //We need boot all additional data required for editor blocks...
  //We will wait till data will be loaded
  for ( var i = 0; i < window.Gutenberg.beforeBoot.length; i++ ) {
    let callback = window.Gutenberg.beforeBoot[i];

    await callback();
  }

  // Toggle features
  const { toggleFeature } = data.dispatch('core/edit-post');
  const { isFeatureActive } = data.select('core/edit-post');

  isFeatureActive('welcomeGuide') && toggleFeature('welcomeGuide');
  isFeatureActive('fullscreenMode') && toggleFeature('fullscreenMode');

  // Disable block patterns
  plugins.getPlugin('edit-post') && plugins.unregisterPlugin('edit-post')

  window._wpLoadGutenbergEditor = new Promise(function (resolve) {
    domReady(async () => {
      const larabergEditor = createEditorElement(target)
      try {
        resolve(editPost.initializeEditor(larabergEditor.id, 'page', 1, getEditorSettings(), overridePost))
        registerCustomBlocks();
        fixReusableBlocks();
      } catch (error) {
        console.error(error)
      }
      await elementReady('.edit-post-layout')
      configureEditor(options)
    })
  })
}

/**
 * Creates the element to render the Gutenberg editor inside of
 * @param {string} target the id of the textarea to render the Editor instead of
 * @return {element}
 */
function createEditorElement (target) {
  const element = document.getElementById(target)
  const editor = document.createElement('DIV')
  editor.id = 'laraberg__editor'
  editor.classList.add('laraberg__editor', 'gutenberg__editor', 'block-editor__container', 'wp-embed-responsive')
  element.parentNode.insertBefore(editor, element)
  element.hidden = true

  editorSettings.target = target

  window.Gutenberg.editor = editor

  return editor
}

function registerCustomBlocks(){
  window.Gutenberg.categories.forEach(({slug, title}) => {
    let category = {
      slug: slug,
      title: title
    }

    const currentCategories = data.select('core/blocks')
                                  .getCategories()
                                  .filter(item => item.slug !== category.slug);

    data.dispatch('core/blocks').setCategories([ category, ...currentCategories ])
  });

   window.Gutenberg.blocks.forEach(({name, block}) => {
      registerBlockType(name, block)
  })
}

function fixReusableBlocks () {
  const coreBlock = getBlockType('core/block')
  unregisterBlockType('core/block')
  coreBlock.attributes = {
    ref: {
      type: 'number'
    }
  }
  registerBlockType('core/block', coreBlock);
}

function getEditorSettings() {
  let options = editorSettings,
      targetElement = document.getElementById(options.target);

  if (targetElement && targetElement.placeholder) {
    options.bodyPlaceholder = targetElement.placeholder
  }

  window.Gutenberg.configure.forEach(callback => {
    options = callback(options);
  })

  return options
}