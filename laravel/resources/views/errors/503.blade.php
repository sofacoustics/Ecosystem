{{--
	Maintenance page (HTTP 503), shown by `php artisan down`.

	Deliberately self-contained: no app layout, no Vite assets, no database,
	auth or route calls. With `php artisan down --render="errors::503"` it is
	rendered once and stored as static HTML, so it must not depend on hashed
	build assets that change during a deployment. See README.md
	("Maintenance mode").
--}}
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<title>Maintenance – SONICOM Ecosystem</title>
	<link rel="icon" href="/favicon.ico">
	<style>
		html, body {
			margin: 0;
			padding: 0;
			height: 100%;
		}
		body {
			display: flex;
			align-items: center;
			justify-content: center;
			background-color: #f3f4f6;
			color: #1f2937;
			font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
			line-height: 1.5;
		}
		.card {
			box-sizing: border-box;
			max-width: 36rem;
			width: calc(100% - 2rem);
			margin: 1rem;
			padding: 2.5rem 2rem;
			background-color: #ffffff;
			border-top: 6px solid #f97316;
			border-radius: 0.5rem;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
			text-align: center;
		}
		.card img {
			height: 4rem;
			width: auto;
			margin-bottom: 1rem;
		}
		h1 {
			margin: 0 0 1rem 0;
			font-size: 1.5rem;
			font-weight: 600;
		}
		p {
			margin: 0.5rem 0;
		}
		.hint {
			margin-top: 1.5rem;
			font-size: 0.875rem;
			color: #6b7280;
		}
	</style>
</head>
<body>
	<main class="card">
		<img src="/images/logo/logo_only.png" alt="SONICOM Ecosystem logo">
		<h1>SONICOM Ecosystem</h1>
		<p>We are currently updating the SONICOM Ecosystem.</p>
		<p>The website will be back shortly. Please try again in a few minutes.</p>
		<p class="hint">Error 503 – Service temporarily unavailable due to maintenance.</p>
	</main>
</body>
</html>
