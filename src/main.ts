import "./app.css";
import { mount } from "svelte";
import App from "./App.svelte";

const app = mount( App, {
	target: document.querySelector( "body" ) as Element
} );

export default app;