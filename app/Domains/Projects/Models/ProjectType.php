<?php
declare(strict_types=1);
namespace App\Domains\Projects\Models;

use App\Models\CactusEntity;
use JMS\Serializer\Annotation as Serializer;

class ProjectType extends CactusEntity
{
    /**
     * @var string $id
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private string $id;

    /**
     * @var string $name
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    private string $name;

    /**
     * @var string $icon
     * @Serializer\SerializedName("icon")
     * @Serializer\Type("string")
     */
    private string $icon;

    /**
     * @var string $slug
     * @Serializer\SerializedName("slug")
     * @Serializer\Type("string")
     */
    private string $slug;

    /**
     * @var bool $visibility
     * @Serializer\SerializedName("visibility")
     * @Serializer\Type("bool")
     */
    private bool $visibility;

    /**
     * @var ?Project[] $projects
     * @Serializer\SerializedName("projects")
     * @Serializer\Type("array<App\Domains\Projects\Models\Project>")
     */
    private ?array $projects;

    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = false): array
    {
        $data = [
          'id' => $this->id,
          'name' => $this->name,
          'icon' => $this->icon,
          'slug' => $this->slug,
          'visibility' => $this->visibility,
        ];

        if($withRelations){
            $data['projects'] = $this->getProjects();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ProjectType
     */
    public function setId(string $id): ProjectType
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProjectType
     */
    public function setName(string $name): ProjectType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisibility(): bool
    {
        return $this->visibility;
    }

    /**
     * @param bool $visibility
     * @return ProjectType
     */
    public function setVisibility(bool $visibility): ProjectType
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getProjects(): ?array
    {
        return $this->projects;
    }

    /**
     * @param ?array $projects
     * @return ProjectType
     */
    public function setProjects(?array $projects): ProjectType
    {
        $this->projects = $projects;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return ProjectType
     */
    public function setIcon(string $icon): ProjectType
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return ProjectType
     */
    public function setSlug(string $slug): ProjectType
    {
        $this->slug = $slug;
        return $this;
    }

}
