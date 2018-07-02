<?php
/**
 * @file
 * Contains the SmartsheetAPI class.
 */

namespace Drupal\smartsheet;

/**
 * Smartsheet REST API wrapper class.
 *
 * @code
 * $instance = SmartsheetAPI::instance();
 * $instance->get('users/me');
 * $instance->post('home/folders', array('name' => 'New folder'));
 * @endcode
 *
 * @package Drupal\smartsheet
 */
class SmartsheetAPI {

  /**
   * Existing instance of the class.
   */
  protected static $instance;

  /**
   * Creates an instance of SmartsheetAPI if not existing already.
   *
   * @return SmartsheetAPI
   *   The Smartsheet API object.
   */
  public static function instance() {
    if (!isset(SmartsheetAPI::$instance)) {
      SmartsheetAPI::$instance = new SmartsheetAPI();
    }
    return SmartsheetAPI::$instance;
  }

  /**
   * Perform a GET request to retrieve one or more resources.
   *
   * @param string $path
   *   The path of the resources to retrieve (eg. sheets/123 or home/folders).
   * @param array $parameters
   *   (optional) Request parameters as a key/value associative array.
   * @param array $options
   *   (optional) An array that can have one or more of the following elements:
   *   - cache: Set to one of the following values to enable caching of the
   *     results:
   *     - CACHE_PERMANENT: Indicates that the item should never be removed
   *       unless explicitly told to using cache_clear_all() with a cache ID.
   *     - CACHE_TEMPORARY: Indicates that the item should be removed at the
   *       next general cache wipe.
   *     - A Unix timestamp: Indicates that the item should be kept at least
   *       until the given time, after which it behaves like CACHE_TEMPORARY.
   *
   * @return object
   *   The resource data provided by the HTTP API.
   *
   * @throws Exception\SmartsheetException
   *   When the HTTP API returned an error.
   */
  public function get($path, array $parameters = array(), array $options = array()) {
    $response = smartsheet_api_request('GET', $path, $parameters, $options);
    return $this->handle($response);
  }

  /**
   * Perform a POST request to create one or more resources.
   *
   * @param string $path
   *   The path of the resources to create (eg. sheets or sheets/{id}/columns).
   * @param array $parameters
   *   Request parameters (request body) as a key/value associative array.
   * @param array $options
   *   (optional) An array that can have one or more of the following elements:
   *   - cache: Set to one of the following values to enable caching of the
   *     results:
   *     - CACHE_PERMANENT: Indicates that the item should never be removed
   *       unless explicitly told to using cache_clear_all() with a cache ID.
   *     - CACHE_TEMPORARY: Indicates that the item should be removed at the
   *       next general cache wipe.
   *     - A Unix timestamp: Indicates that the item should be kept at least
   *       until the given time, after which it behaves like CACHE_TEMPORARY.
   *
   * @return object
   *   An object containing the following properties:
   *   - resultCode: A Smartsheet internal code indicating if the request was a
   *     success or not.
   *   - result: An object containing the description of the created resource.
   *   - message: A human-friendly message indicating if the request was a
   *     success or not.
   *
   * @throws Exception\SmartsheetException
   *   When the HTTP API returned an error.
   */
  public function post($path, array $parameters, array $options = array()) {
    $response = smartsheet_api_request('POST', $path, $parameters, $options);
    return $this->handle($response);
  }

  /**
   * Perform a PUT request to update a resource.
   *
   * @param string $path
   *   The path of the resource to update (eg. folders/{id} or sheets/{id}).
   * @param array $parameters
   *   Request parameters (request body) as a key/value associative array.
   * @param array $options
   *   (optional) An array that can have one or more of the following elements:
   *   - cache: Set to one of the following values to enable caching of the
   *     results:
   *     - CACHE_PERMANENT: Indicates that the item should never be removed
   *       unless explicitly told to using cache_clear_all() with a cache ID.
   *     - CACHE_TEMPORARY: Indicates that the item should be removed at the
   *       next general cache wipe.
   *     - A Unix timestamp: Indicates that the item should be kept at least
   *       until the given time, after which it behaves like CACHE_TEMPORARY.
   *
   * @return object
   *   An object containing the following properties:
   *   - resultCode: A Smartsheet internal code indicating if the request was a
   *     success or not.
   *   - result: An object containing the description of the updated resource.
   *   - message: A human-friendly message indicating if the request was a
   *     success or not.
   *
   * @throws Exception\SmartsheetException
   *   When the HTTP API returned an error.
   */
  public function put($path, array $parameters, array $options = array()) {
    $response = smartsheet_api_request('PUT', $path, $parameters, $options);
    return $this->handle($response);
  }

  /**
   * Perform a DELETE request to update a resource.
   *
   * @param string $path
   *   The path of the resource to delete (eg. folders/{id} or sheets/{id}).
   * @param array $parameters
   *   (optional) Request parameters as a key/value associative array.
   *   According to the RFC2616, the body will be ignored in case of a DELETE
   *   request.
   * @param array $options
   *   (optional) An array that can have one or more of the following elements:
   *   - cache: Set to one of the following values to enable caching of the
   *     results:
   *     - CACHE_PERMANENT: Indicates that the item should never be removed
   *       unless explicitly told to using cache_clear_all() with a cache ID.
   *     - CACHE_TEMPORARY: Indicates that the item should be removed at the
   *       next general cache wipe.
   *     - A Unix timestamp: Indicates that the item should be kept at least
   *       until the given time, after which it behaves like CACHE_TEMPORARY.
   *
   * @return object
   *   An object containing the following properties:
   *   - resultCode: A Smartsheet internal code indicating if the request was a
   *     success or not.
   *   - message: A human-friendly message indicating if the request was a
   *     success or not.
   *
   * @throws Exception\SmartsheetException
   *   When the HTTP API returned an error.
   */
  public function delete($path, array $parameters = array(), array $options = array()) {
    $response = smartsheet_api_request('DELETE', $path, $parameters, $options);
    return $this->handle($response);
  }

  /**
   * Handles a response retreived from the HTTP API.
   *
   * @param object $response
   *   A raw response object as provided by the HTTP API.
   *
   * @return object
   *   The data provided by the HTTP API.
   *
   * @throws Exception\SmartsheetException
   *   If the response failed at the HTTP level.
   */
  private function handle($response) {
    switch ($response->code) {
      case 200:
        return json_decode($response->data);

      case 400:
        // BAD REQUEST
      case 401:
        // NOT AUTHORIZED
      case 403:
        // FORBIDDEN
      case 404:
        // NOT FOUND
      case 405:
        // METHOD NOT SUPPORTED
      case 500:
        // INTERNAL SERVER ERROR
      case 503:
        // SERVICE UNAVAILABLE
      default:
        throw new Exception\SmartsheetException(format_string('Unexpected response code @code: !data.', array(
          '@code' => $response->code,
          '!data' => $response->data,
        )), $response->code);
    }
  }

}
