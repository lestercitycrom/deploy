<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomersController {
    public function __invoke(Request $request, Response $response, $args) {
        $data = [
            'message' => 'This is the customers endpoint'
        ];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
