<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Dto\CoasterDto;
use App\Repositories\CoasterRepository;
use App\Repositories\CoasterRepositoryInterface;
use App\Repositories\WagonRepository;
use App\Repositories\WagonRepositoryInterface;
use CodeIgniter\HTTP\ResponseInterface;

final class CoasterController extends BaseController
{
    private readonly CoasterRepositoryInterface $coasterRepository;
    private readonly WagonRepositoryInterface $wagonRepository;

    public function __construct()
    {
        $this->coasterRepository = new CoasterRepository();
        $this->wagonRepository = new WagonRepository();
    }

    public function getAll(): ResponseInterface
    {
        return $this->response->setJSON(
            array_map(
                callback: function ($coaster) {
                     return $coaster->toArray();
                },
                array: $this->coasterRepository->getAll()
            )
        );
    }

    public function get(int $id): ResponseInterface
    {
        $coaster = $this->coasterRepository->getById($id);

        if (empty($coaster)) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([])
                ;
        }

        return $this->response->setJSON($coaster->toArray());
    }

    public function create(): ResponseInterface
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON([])
                ;
        }
        $body = $this->request->getBody();
        $coasterDto = CoasterDto::buildFromJSON($body);
        if ($coasterDto->getId() !== null) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_CONFLICT)
                ->setJSON([])
                ;
        }

        $this->coasterRepository->create($coasterDto);

        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_CREATED)
            ->setJSON($body)
            ;
    }

    public function update(int $id): ResponseInterface
    {
        if ($this->request->getMethod() !== 'PUT') {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON([])
                ;
        }
        $coasterDto = $this->coasterRepository->getById($id);
        if ($coasterDto === null) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([])
                ;
        }

        $body = $this->request->getJSON(true);
        $coasterDto->update($body);
        $this->coasterRepository->update($coasterDto);

        return $this->response->setJSON($coasterDto->toArray());

    }

    public function delete(int $id): ResponseInterface
    {
        if ($this->request->getMethod() !== 'DELETE') {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON([])
                ;
        }
        $coasterDto = $this->coasterRepository->getById($id);
        if ($coasterDto === null) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([])
                ;
        }

        $result = $this->coasterRepository->delete($id);
        if ($result) {
            $this->wagonRepository->deleteByCoastId($id);
        }

        $response = $this->response->setJSON([]);

        return $result
            ? $response->setStatusCode(ResponseInterface::HTTP_OK)
            : $response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
    }
}
