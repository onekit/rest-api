<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user_get", "user_list", "Default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_get", "user_list", "Default"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Email
     * @OA\Property(
     *      property="username",
     *      type="string",
     *      description="Email used as Login")
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_get", "Default"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @OA\Property(
     *      property="password",
     *      type="string",
     *      description="Password")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_get", "user_list"})
     * @Assert\Length(min=2, max=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_get", "user_list"})
     * @Assert\Length(min=2, max=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Groups({"user_get", "user_list"})
     * @Assert\NotBlank(message="Please provide a phone number")
     * @Assert\Length(
     *     min=8,
     *     max=16,
     *     minMessage="The number field must contain at least one number",
     *     maxMessage="The number field must contain maximum 16 numbers"
     * )
     * @Assert\Regex(
     *     pattern="/^\+[0-9]+$/",
     *     message="Only phone numbers allowed +375-xxxx format"
     * )
     */
    private $phone;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"user_get"})
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({"user_get"})
     */
    private $updated;

    /**
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Picture",
     *      mappedBy="user", cascade={"persist","remove"}, orphanRemoval=true
     * )
     * @Groups({"user_get"})
     */
    private $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * @return ArrayCollection|PersistentCollection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param PersistentCollection $pictures
     */
    public function setPictures(PersistentCollection $pictures): void
    {
        $this->pictures = $pictures;
    }

}

