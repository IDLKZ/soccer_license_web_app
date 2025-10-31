<?php

namespace App\Constants;

class FileExtensionConstants
{
    // Folder paths
    public const LEAGUE_FOLDER_PATH = 'leagues';
    public const DOCUMENT_FOLDER_PATH = 'documents';
    public const CLUB_FOLDER_PATH = 'clubs';

    // Image extensions
    public const IMAGE_EXTENSIONS = [
        '.jpg',
        '.jpeg',
        '.png',
        '.gif',
        '.bmp',
        '.svg',
        '.webp',
        '.tiff',
        '.ico',
        '.heic',
    ];

    // Video extensions
    public const VIDEO_EXTENSIONS = [
        '.mp4',
        '.avi',
        '.mkv',
        '.mov',
        '.flv',
        '.wmv',
        '.webm',
        '.mpeg',
        '.3gp',
        '.m4v',
    ];

    // Audio extensions
    public const AUDIO_EXTENSIONS = [
        '.mp3',
        '.wav',
        '.aac',
        '.flac',
        '.ogg',
        '.m4a',
        '.wma',
        '.amr',
        '.opus',
        '.aiff',
    ];

    // Document extensions
    public const DOCUMENT_EXTENSIONS = [
        '.pdf',
        '.doc',
        '.docx',
        '.xls',
        '.xlsx',
        '.ppt',
        '.pptx',
        '.txt',
        '.csv',
        '.rtf',
        '.odt',
        '.ods',
        '.odp',
        '.epub',
        '.pages',
        '.numbers',
        '.key',
    ];

    // Archive extensions
    public const ARCHIVE_EXTENSIONS = [
        '.zip',
        '.rar',
        '.7z',
        '.tar',
        '.gz',
        '.bz2',
        '.xz',
        '.iso',
        '.tgz',
        '.tar.gz',
    ];

    // Text file extensions
    public const TEXT_EXTENSIONS = [
        '.txt',
        '.log',
        '.md',
        '.yaml',
        '.yml',
        '.json',
        '.xml',
        '.html',
        '.css',
        '.js',
    ];

    // Payment document extensions
    public const PAYMENT_DOCUMENT_EXTENSIONS = [
        '.jpg',
        '.jpeg',
        '.png',
        '.pdf',
    ];

    /**
     * Get all extensions combined
     */
    public static function getAllExtensions(): array
    {
        return array_merge(
            self::IMAGE_EXTENSIONS,
            self::VIDEO_EXTENSIONS,
            self::AUDIO_EXTENSIONS,
            self::DOCUMENT_EXTENSIONS,
            self::ARCHIVE_EXTENSIONS,
            self::TEXT_EXTENSIONS
        );
    }

    /**
     * Check if extension is valid
     */
    public static function isValidExtension(string $extension, array $allowedExtensions): bool
    {
        return in_array(strtolower($extension), $allowedExtensions);
    }

    /**
     * Get user profile photo directory
     */
    public static function userProfilePhotoDirectory(string $username): string
    {
        return "users/photos/{$username}";
    }

    /**
     * Get application document directory
     */
    public static function applicationDocumentDirectory(int $id, string $departmentStr): string
    {
        return "applications/documents/{$id}/{$departmentStr}";
    }
}
