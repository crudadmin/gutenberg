/**
 * react is provided by the page as a global.
 *
 * It cannot be a plain rollup external: several bundled commonjs packages pull
 * react in with require(), and those calls are not rewired to the iife argument,
 * which would leave a bare require in the output.
 *
 * The export list mirrors react 16.9.0.
 */
const React = window.React

export const Children = React.Children
export const Component = React.Component
export const Fragment = React.Fragment
export const Profiler = React.Profiler
export const PureComponent = React.PureComponent
export const StrictMode = React.StrictMode
export const Suspense = React.Suspense
export const __SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED = React.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED
export const cloneElement = React.cloneElement
export const createContext = React.createContext
export const createElement = React.createElement
export const createFactory = React.createFactory
export const createRef = React.createRef
export const forwardRef = React.forwardRef
export const isValidElement = React.isValidElement
export const lazy = React.lazy
export const memo = React.memo
export const unstable_SuspenseList = React.unstable_SuspenseList
export const unstable_withSuspenseConfig = React.unstable_withSuspenseConfig
export const useCallback = React.useCallback
export const useContext = React.useContext
export const useDebugValue = React.useDebugValue
export const useEffect = React.useEffect
export const useImperativeHandle = React.useImperativeHandle
export const useLayoutEffect = React.useLayoutEffect
export const useMemo = React.useMemo
export const useReducer = React.useReducer
export const useRef = React.useRef
export const useState = React.useState
export const version = React.version

export default React
