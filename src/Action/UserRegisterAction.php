<?php

namespace App\Action;

use App\Attributes\Route;
use App\Core\CsrfToken;
use App\Core\Middleware;
use App\Core\RateLimiter;
use App\Core\Request;
use App\Domain\User\UserService;
use App\Exception\BusinessRuleException;
use App\Exception\TooManyRequestsException;
use App\Exception\UnauthorizedException;
use App\Responder\UserRegisterResponder;
use App\Utility\Redirect;

#[Route(
  path: '/register',
  method: ['GET', 'POST'],
  middlewares: [
    Middleware::IsAnonymous => ''
  ]
)]
final readonly class UserRegisterAction {
  public function __construct(
    private UserService           $service,
    private UserRegisterResponder $responder,
    private CsrfToken             $csrfToken,
  ) {
  }

  /**
   * @throws UnauthorizedException
   * @throws TooManyRequestsException
   */
  public function __invoke(
    Request     $request,
    RateLimiter $rateLimiter,
  ) {
    // Handle POST
    if ($request->method === 'POST') {
      if (!$this->csrfToken->validate()) {
        throw new UnauthorizedException();
      }

      $email = $request->input('email', '');
      $ip = $_SERVER['REMOTE_ADDR'] ?? '';

      $ipKey = 'rego_ip_' . hash('sha256', $ip);
      $emailKey = 'rego_email_' . hash('sha256', $email);

      // Check rate limits
      if (
        $rateLimiter->isRateLimited($ipKey, 5)
        || $rateLimiter->isRateLimited($emailKey, 3)
      ) {
        throw new TooManyRequestsException();
      }

      // Record attempts
      $rateLimiter->hit($ipKey, 300);
      $rateLimiter->hit($emailKey, 300);

      try {
        $this->service->insert(
          $email,
          $request->input('password')
        );

        // Clear limiters on successful registration
        $rateLimiter->clear($ipKey);
        $rateLimiter->clear($emailKey);

        return new Redirect('/login');
      } catch (BusinessRuleException|\DateMalformedStringException $e) {
        // TODO: Handle errors
      }
    }

    // Handle GET
    ($this->responder)($this->csrfToken->get());
  }
}