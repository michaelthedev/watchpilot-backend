<?php

use App\Services\Encryption;
use Carbon\Carbon;
use Pecee\Http\Input\InputHandler;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;
use Rakit\Validation\Validator;

/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param array|string|null $parameters
 * @param array|null $getParams
 * @return Url
 * @throws InvalidArgumentException
 */
function url(?string $name = null, array|string|null $parameters = null, ?array $getParams = null): Url
{
    return Router::getUrl($name, $parameters, $getParams);
}

function response(): Response
{
    return Router::response();
}

function request(): Request
{
    return Router::request();
}

/**
 * Get input class
 * @param string|null $index Parameter index name
 * @param mixed|null $defaultValue Default return value
 * @param array ...$methods Default methods
 * @return InputHandler|array|string|null
 */
function input(string $index = null, mixed $defaultValue = null, ...$methods): array|string|InputHandler|null
{
    if ($index !== null) {
        return request()->getInputHandler()->value($index, $defaultValue, ...$methods);
    }

    return request()->getInputHandler();
}

function redirect(string $url, ?int $code = null): void
{
    if ($code !== null) {
        response()->httpCode($code);
    }

    response()->redirect($url);
}

/**
 * Get current csrf-token
 * @return string|null
 */
function csrf_token(): ?string
{
    $baseVerifier = Router::router()->getCsrfVerifier();
    return $baseVerifier?->getTokenProvider()->getToken();
}

function validate(array $rules, ?array $data = null): array
{
	$validator = new Validator;
	$validation = $validator->validate($data ?? (input()->getOriginalPost() + $_FILES), $rules, [
		'required' => ':attribute is required',
		'email' => ':attribute must be a valid email address',
		'numeric' => ':attribute must be numeric',
		'in' => ':attribute must be one of :allowed_values',
	]);

	if ($validation->fails()) {
		$errorMessage = implode('; ', $validation->errors()->all());
		response()->httpCode(400)->json([
			'error' => true,
			'message' => $errorMessage,
		]);
	}

	return $validation->getValidData();
}

function config(string $configKey, $default = ''): string|array
{
    $ex = explode('.', $configKey);
    $file = $ex[0];
    $key = $ex[1];

    if (file_exists(CONFIG_PATH . "/$file.php")) {
        $config = require CONFIG_PATH . "/$file.php";

        if (!empty($ex[2])) {
            $value = $config[$key][$ex[2]] ?? '';
        } else {
            $value = $config[$key] ?? '';
        }
    }
    return $value ?? $default;
}

function isValidDate(string $date, string $format = 'd-m-Y'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function uploads_path(string $path): string
{
	return 'uploads/'.$path;
}

if (!function_exists('encrypt')) {
	function encrypt(mixed $data): string
	{
		$service = new Encryption();

		return $service->encrypt($data);
	}

	function decrypt(mixed $data): string
	{
		$service = new Encryption();

		return $service->decrypt($data);
	}
}

function asset(string $path): string
{
	$baseUrl = config('app.assetBase');
	return $baseUrl .'/'. $path;
}

function now(): Carbon
{
	return Carbon::now(config('app.timezone'));
}