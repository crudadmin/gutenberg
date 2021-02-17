/**
 * Registers a custom block to the editor
 * @param {string} name The namespaced name of the block (eg: my-module/my-block)
 * @param {Object} block The Gutenberg block object
 */
export function registerBlock (name, block) {
    window.Gutenberg.blocks.push({ name, block });
}

/**
 * Adds a category to the category list
 * @param {String} title - The title for the category (eg: My Category)
 * @param {String} slug - The slug for the category (eg: my-category)
 */
export function registerCategory (title, slug) {
    window.Gutenberg.categories.push({ title, slug });
}