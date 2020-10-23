<?php

namespace App\Manager;

use diversen\gps;
use Doctrine\ORM\EntityManager;
use App\Repository\PictureRepository;
use App\Entity\Picture;
use App\DTO\CreatePicture;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class PictureManager extends ApiManager
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    private $security;

    /**
     * @var PictureRepository
     */
    protected $pictureRepository;

    /**
     * @var CacheManager
     */
    protected $imagineCacheManager;

    /**
     * @var RequestStack
     */
    protected $requestStack;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, Security $security, CacheManager $cacheManager, RequestStack $requestStack, PictureRepository $pictureRepository, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->imagineCacheManager = $cacheManager;
        $this->requestStack = $requestStack;
        $this->pictureRepository = $pictureRepository;
        $this->validator = $validator;
    }

    public function assignPicture(Picture $picture, CreatePicture $createPicture): Picture
    {
        if ($createPicture->title) {
            $picture->setTitle($createPicture->title);
        }
        if ($createPicture->file) {
            $picture->setFile($createPicture->file);
        }

        return $picture;
    }


    public function update(Picture $picture): Picture
    {
        $this->entityManager->persist($picture);
        $this->entityManager->flush();
        return $picture;
    }

    /**
     * @param Picture $picture
     * @param CreatePicture|null $createPicture
     * @return Picture|JsonResponse
     */
    public function updatePicture(Picture $picture, CreatePicture $createPicture = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        $picture = $picture ? $picture : new Picture();
        $createPicture = $createPicture ? $createPicture : new createPicture();
        $createPicture->title = $request->request->get('title');
        $createPicture->file = $request->files->get('file');
        $picture->setUser($this->security->getUser());
        $picture = $this->assignPicture($picture, $createPicture);
        $file = $picture->getFile();
        $picture->setFileSize($file->getSize());
        $info = exif_read_data($file);
        $picture->setHeight($info['COMPUTED']['Height']);
        $picture->setWidth($info['COMPUTED']['Width']);

        $constraints = $this->validator->validate($picture);
        $g = new gps();
        $gps = $g->getGpsPosition($file);
        if (empty($gps)) {
            return new JsonResponse(['errors' => "GPS data not found"], 400);
        } else {
            $picture->setLat($gps['latitude']);
            $picture->setLng($gps['longitude']);
        }
        if ($constraints->count()) {
            return new JsonResponse(['errors' => $this->handleError($constraints)], 400);
        }
        $picture = $this->update($picture); //update for getting filename
        $baseURI = $this->imagineCacheManager->generateUrl($picture->getFilename(), 'my_thumb');
        $picture->setFileUrl($baseURI);
        return $this->update($picture);
    }

    public function find($id): ?Picture
    {
        return $this->pictureRepository->find($id);
    }

    public function all(): array
    {
        return $this->pictureRepository->findAll();
    }

    public function delete($id): JsonResponse
    {
        $picture = $this->find($id);
        if (!$picture) {
            return new JsonResponse('Picture not found', 404);
        }
        $this->entityManager->remove($picture);
        $this->entityManager->flush();

        return new JsonResponse(null, 204);
    }


}