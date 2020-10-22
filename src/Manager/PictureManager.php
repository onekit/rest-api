<?php

namespace App\Manager;

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
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


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
     * @var UploaderHelper
     */
    protected $uploaderHelper;

    /**
     * @var RequestStack
     */
    protected $requestStack;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, Security $security, CacheManager $cacheManager, UploaderHelper $uploaderHelper, RequestStack $requestStack, PictureRepository $pictureRepository, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->imagineCacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
        $this->requestStack = $requestStack;
        $this->pictureRepository = $pictureRepository;
        $this->validator = $validator;
    }

    public function assignPicture(Picture $picture, CreatePicture $createPicture): Picture
    {
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
        $picture = $picture ? $picture : new Picture();
        $picture->setUser($this->security->getUser());
        $request = $this->requestStack->getCurrentRequest();
        $picture->setFile($request->files->get('file'));

        $file = !is_null($picture) ? $picture->getFile() : null;
        $baseURI = null;
        $picture->setFile($createPicture->file);
        $this->update($picture);
        if ($file) {
            $picturePath = $this->uploaderHelper->asset($file, 'file');
            $baseURI = $this->imagineCacheManager->generateUrl($picturePath, 'picture');
        }
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