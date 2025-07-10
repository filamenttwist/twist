# Obridge Middleware Improvements

This document outlines the comprehensive improvements made to the `ObridgeMiddleware` class.

## Overview

The `ObridgeMiddleware` has been significantly enhanced with security features, performance optimizations, and better error handling to provide a robust authentication mechanism for the Obridge system.

## Key Improvements

### 1. Enhanced Security

#### Rate Limiting
- **Brute Force Protection**: Prevents unlimited authentication attempts
- **Configurable Limits**: Set max attempts and decay time via configuration
- **IP + Name Based**: Rate limiting considers both IP address and obridge name

#### Input Validation
- **Header Validation**: Proper validation of required headers
- **Credential Sanitization**: Trimming and validation of input credentials
- **Empty Value Handling**: Proper handling of missing or empty credentials

#### Secret Management
- **Hash Support**: Optional secret hashing for enhanced security
- **Secure Generation**: Built-in secure secret generation
- **Hidden Attributes**: Secrets are hidden in model serialization

### 2. Performance Optimizations

#### Caching
- **Authentication Caching**: Cache successful authentications to reduce database queries
- **Configurable TTL**: Adjustable cache time-to-live
- **Smart Cache Keys**: MD5-based cache keys for consistency

#### Database Optimizations
- **Active Scope**: Only query active obridges
- **Efficient Updates**: Async last-used timestamp updates
- **Proper Indexing**: Database indexes for better query performance

### 3. Enhanced Monitoring & Logging

#### Comprehensive Logging
- **Authentication Attempts**: Log all authentication attempts with details
- **Structured Data**: Consistent log format with relevant context
- **Configurable Channels**: Custom log channel for obridge activities
- **Status Tracking**: Track success, failure, and rate limiting events

#### Activity Tracking
- **Last Used Timestamps**: Track when each obridge was last used
- **Usage Analytics**: Support for usage pattern analysis

### 4. Better Error Handling

#### Detailed Error Responses
- **Specific Messages**: Clear error messages for different failure scenarios
- **Error Codes**: Standardized error codes for API consumers
- **Timestamps**: Include timestamps in error responses

#### HTTP Status Codes
- **401 Unauthorized**: For authentication failures
- **429 Too Many Requests**: For rate limiting
- **Proper Response Format**: Consistent JSON error responses

### 5. Enhanced Model Features

#### Obridge Model Improvements
- **Active/Inactive Status**: Support for enabling/disabling obridges
- **Descriptions**: Optional descriptions for better management
- **Factories Support**: Laravel factory support for testing
- **Scopes**: Convenient query scopes for common operations
- **Helper Methods**: Utility methods for common operations

### 6. Configuration Management

#### Centralized Configuration
```php
// config/obridge.php
return [
    'rate_limit' => [
        'max_attempts' => env('OBRIDGE_MAX_ATTEMPTS', 10),
        'decay_minutes' => env('OBRIDGE_DECAY_MINUTES', 60),
    ],
    'cache' => [
        'ttl_minutes' => env('OBRIDGE_CACHE_TTL', 15),
    ],
    'logging' => [
        'channel' => env('OBRIDGE_LOG_CHANNEL', 'obridge'),
        'log_attempts' => env('OBRIDGE_LOG_ATTEMPTS', true),
    ],
    'security' => [
        'hash_secrets' => env('OBRIDGE_HASH_SECRETS', false),
        'require_https' => env('OBRIDGE_REQUIRE_HTTPS', false),
    ],
];
```

## Usage Examples

### Basic Usage
```php
// In your routes or controllers
Route::middleware(['obridge'])->group(function () {
    Route::get('/api/data', function (Request $request) {
        $obridge = $request->get('authenticated_obridge');
        return response()->json(['obridge' => $obridge->name]);
    });
});
```

### Custom Configuration
```bash
# Environment variables
OBRIDGE_MAX_ATTEMPTS=5
OBRIDGE_DECAY_MINUTES=30
OBRIDGE_CACHE_TTL=10
OBRIDGE_LOG_ATTEMPTS=true
OBRIDGE_HASH_SECRETS=true
```

### Programmatic Usage
```php
// Create an obridge
$obridge = Obridge::create([
    'name' => 'my-api-bridge',
    'secret' => Obridge::generateSecret(),
    'description' => 'API bridge for external service',
    'is_active' => true,
]);

// Check if obridge is active
if ($obridge->isActive()) {
    // Process request
}

// Update last used timestamp
$obridge->updateLastUsed();
```

## Database Schema

The improved obridge table includes:
- `id`: Primary key
- `name`: Unique identifier
- `secret`: Authentication secret (supports hashing)
- `description`: Optional description
- `is_active`: Enable/disable flag
- `last_used_at`: Last usage timestamp
- `created_at`, `updated_at`: Laravel timestamps

## Filament Admin Interface

The Filament resource has been enhanced with:
- **Toggle for Active Status**: Easy enable/disable
- **Description Field**: Better documentation
- **Last Used Display**: Monitor usage patterns
- **Filters**: Filter by active status
- **Auto-generation**: Automatic secret generation
- **Validation**: Proper form validation

## Testing

Example test scenarios are provided in `ObridgeMiddlewareExamples.php`:
- Valid credential authentication
- Invalid credential rejection
- Rate limiting functionality
- Caching behavior
- Last used timestamp updates

## Migration Path

To upgrade from the old middleware:

1. **Publish Configuration**: `php artisan vendor:publish --tag=obridge-config`
2. **Run Migrations**: The new migration will add required fields
3. **Update Environment**: Add new environment variables as needed
4. **Test**: Verify functionality with existing obridges

## Security Considerations

- **HTTPS**: Consider enabling `require_https` in production
- **Secret Hashing**: Enable `hash_secrets` for sensitive environments
- **Rate Limiting**: Adjust limits based on your use case
- **Logging**: Monitor logs for suspicious activity
- **Cache**: Ensure cache is properly secured

## Performance Notes

- **Database Queries**: Reduced through intelligent caching
- **Memory Usage**: Minimal additional memory overhead
- **Response Time**: Improved through caching and optimizations
- **Scalability**: Designed to handle high-volume scenarios

## Backward Compatibility

The improvements maintain backward compatibility with existing obridge implementations while adding new optional features. Existing obridges will continue to work without modification.
