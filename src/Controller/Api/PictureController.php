<?php
namespace App\Controller\Api;

use App\DTO\CreatePicture;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use App\Entity\Picture;
use App\Manager\PictureManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Sensio\Route("/pictures")
 */
class PictureController extends AbstractFOSRestController
{

    /**
     * @var PictureManager
     */
    protected $pictureManager;

    /**
     * @param PictureManager $pictureManager
     */
    public function __construct(PictureManager $pictureManager)
    {
        $this->pictureManager = $pictureManager;
    }

    /**
     * @Rest\Get("", name="picture_list")
     * @Rest\View(serializerGroups={"picture_list"})
     */
    public function pictureList(): array
    {
        return $this->pictureManager->all();
    }

    /**
     * @Rest\Post("", name="picture_create")
     * @Rest\View(statusCode=201, serializerGroups={"default", "picture"})
     * @Sensio\ParamConverter("createPicture", converter="fos_rest.request_body")
     * @param CreatePicture $createPicture
     * @return JsonResponse|Picture
     */
    public function pictureCreate(CreatePicture $createPicture)
    {
        return $this->pictureManager->updatePicture(new Picture(), $createPicture);
    }

    /**
     * @Sensio\Security("has_role('ROLE_ADMIN')")
     * @Rest\Delete("/{id}", name="picture_delete", requirements={"id" = "\d+"})
     * @Sensio\ParamConverter("picture", converter="doctrine.orm")
     * @Rest\View()
     *
     * @param Picture $picture
     */
    public function pictureDelete(Picture $picture)
    {
        $this->pictureManager->delete($picture);
    }

}