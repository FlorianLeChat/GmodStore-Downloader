<p>
	A token can be generated at the following address (<strong
		>account login required</strong
	>):
	<a
		rel="noopener noreferrer"
		href="https://www.gmodstore.com/settings/personal-access-tokens"
		target="_blank"
		>https://www.gmodstore.com/settings/personal-access-tokens</a
	><br />

	The permissions to be granted when creating the token are:
	<code>products:read</code>, <code>product-versions:read</code>,
	<code>product-versions:download</code>, <code>users:read</code> and
	<code>user-purchases:read</code>.<br />

	Please note that the token will be displayed only once when it is created,
	so remember to save it somewhere safe!<br />

	Once retrieved, simply paste it into the field below.
	<strong
		>Do not share it with others unless you know what you are doing</strong
	>.
</p>

<form method="GET">
	<label for="token">Account authentication token :</label>

	<input
		id="token"
		name="token"
		type="text"
		value="0fa80099-f11c-47af-bcfe-babafac2edf2|QWTXV4Jlhud76krZPAK9skQs8vsM1dH8MZAAEKSV"
		spellCheck="false"
		autoComplete="off"
		required
	/>

	<input type="submit" value="Connect" />
</form>

<small>This website is fully client-side, no data is sent to the server.</small>

<style>
	input[type="text"] {
		/* Champ de saisie pour le jeton */
		width: calc(100% - 0.5rem);
		display: block;
		max-width: 30rem;
		margin-bottom: 1rem;
	}
</style>