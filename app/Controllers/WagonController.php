<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dto\WagonDto;
use App\Repositories\CoasterRepository;
use App\Repositories\WagonRepository;
use App\Repositories\WagonRepositoryInterface;
use CodeIgniter\HTTP\ResponseInterface;

class WagonController extends BaseController
{
    private readonly WagonRepositoryInterface $wagonRepository;
    private readonly CoasterRepository $coasterRepository;

    public function __construct()
    {
        $this->wagonRepository = new WagonRepository();
        $this->coasterRepository = new CoasterRepository();
    }

    public function getAll(): ResponseInterface
    {
        return $this->response->setJSON(
            array_map(
                callback: function ($wagon) {
                    return $wagon->toArray();
                },
                array: $this->wagonRepository->getAll()
            )
        );
    }

    public function get(int $id): ResponseInterface
    {
        $wagon = $this->wagonRepository->getById($id);

        if (empty($wagon)) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([])
                ;
        }

        return $this->response->setJSON($wagon->toArray());
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
        $wagonDto = WagonDto::buildFromJSON($body);
        $coasterDto = $this->coasterRepository->getById($wagonDto->getCoasterId());
        if ($wagonDto->getId() !== null || $coasterDto === null) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE)
                ->setJSON([])
                ;
        }

        $this->wagonRepository->create($wagonDto);

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
        $wagonDto = $this->wagonRepository->getById($id);
        if ($wagonDto === null) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([])
                ;
        }

        $body = $this->request->getJSON(true);
        $wagonDto->update($body);
        $this->wagonRepository->update($wagonDto);

        return $this->response->setJSON($wagonDto->toArray());

    }

    public function delete(int $id): ResponseInterface
    {
        if ($this->request->getMethod() !== 'DELETE') {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON([])
                ;
        }
        $wagonDto = $this->wagonRepository->getById($id);
        if ($wagonDto === null) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([])
                ;
        }

        $result = $this->wagonRepository->delete($id);

        $response = $this->response->setJSON([]);

        return $result
            ? $response->setStatusCode(ResponseInterface::HTTP_OK)
            : $response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
    }
}
