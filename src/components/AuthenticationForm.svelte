<p>
	A token can be generated at the following address
	(<strong>account login required</strong>):
	<a
		rel="noopener noreferrer"
		href="https://www.gmodstore.com/settings/personal-access-tokens"
		target="_blank"
	>
		https://www.gmodstore.com/settings/personal-access-tokens
	</a><br />

	The permissions to be granted when creating the token are:
	<code>products:read</code>, <code>product-versions:read</code>,
	<code>product-versions:download</code>, <code>users:read</code> and
	<code>user-purchases:read</code>.<br />

	Please note that the token will be displayed only once when it is created,
	so remember to save it somewhere safe!<br />

	Once retrieved, simply paste it into the field below.
	<strong>
		Do not share it with others unless you know what you are doing.
	</strong>.
</p>

<form method="GET">
	<label for="token">Account authentication token :</label>

	<input
		id="token"
		name="token"
		type="text"
		spellCheck="false"
		autoComplete="off"
		required
	/>

	<input type="submit" value="Connect" />

	<em>
		Since July 2025, this website now runs entirely in your browser.
		<br />
		Your token is used only to communicate securely with the GmodStore API —
		it is never stored or shared elsewhere.
		<br />
		The legacy PHP version, which relied on an intermediate server,
		<strong>is no longer available</strong>.
	</em>

	<em>
		Since March 2026, this website has been moved to my personal GitLab instance.
		<br />
		GitLab Pages is used and provides the <strong>same privacy guarantees as before</strong>.
		<br />
		The server is now located in France; therefore, the website falls under the GDPR.
	</em>
</form>

<style>
	input[type="text"] {
		width: calc(100% - 0.5rem);
		display: block;
		max-width: 30rem;
		margin-bottom: 1rem;
	}

	em {
		display: block;
		font-size: 0.9rem;
		margin-top: 1rem;
	}
</style>