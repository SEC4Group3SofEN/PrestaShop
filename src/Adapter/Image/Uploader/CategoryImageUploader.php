<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace PrestaShop\PrestaShop\Adapter\Image\Uploader;

use PrestaShop\PrestaShop\Core\Domain\Category\ValueObject\CategoryId;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/** One service that uploads all category images */
class CategoryImageUploader
{
    /**
     * @var ImageUploaderInterface
     */
    private $categoryCoverUploader;

    /**
     * @var ImageUploaderInterface
     */
    private $categoryThumbnailUploader;

    /**
     * @var ImageUploaderInterface
     */
    private $categoryMenuThumbnailUploader;

    public function __construct(
        ImageUploaderInterface $categoryCoverUploader,
        ImageUploaderInterface $categoryThumbnailUploader,
        ImageUploaderInterface $categoryMenuThumbnailUploader
    ) {
        $this->categoryCoverUploader = $categoryCoverUploader;
        $this->categoryThumbnailUploader = $categoryThumbnailUploader;
        $this->categoryMenuThumbnailUploader = $categoryMenuThumbnailUploader;
    }

    /**
     * @param CategoryId $categoryId
     * @param UploadedFile|null $coverImage
     * @param UploadedFile|null $thumbnailImage
     * @param UploadedFile[] $menuThumbnailImages
     */
    public function uploadImages(
        CategoryId $categoryId,
        UploadedFile $coverImage = null,
        UploadedFile $thumbnailImage = null,
        array $menuThumbnailImages = []
    ): void {
        if (null !== $coverImage) {
            $this->categoryCoverUploader->upload($categoryId->getValue(), $coverImage);
        }

        if (null !== $thumbnailImage) {
            $this->categoryThumbnailUploader->upload($categoryId->getValue(), $thumbnailImage);
        }

        if (!empty($menuThumbnailImages)) {
            foreach ($menuThumbnailImages as $menuThumbnail) {
                $this->categoryMenuThumbnailUploader->upload($categoryId->getValue(), $menuThumbnail);
            }
        }
    }
}
