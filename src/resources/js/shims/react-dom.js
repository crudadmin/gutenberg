/**
 * react-dom is provided by the page as a global, the same way react is.
 *
 * It cannot be declared as a plain rollup external: vite only wires up the
 * output.globals mapping for externals imported by default, and every consumer
 * here imports react-dom by name only, which would leave the references bound
 * to an undefined react_dom identifier.
 */
const ReactDOM = window.ReactDOM

export const createPortal = ReactDOM.createPortal
export const findDOMNode = ReactDOM.findDOMNode
export const render = ReactDOM.render
export const unmountComponentAtNode = ReactDOM.unmountComponentAtNode

export default ReactDOM
