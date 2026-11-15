<?php

declare(strict_types=1);

$whatsappNumber = '2349161426400';

$whatsappMessage = 'Hello! I would like you to help me build a website. I would love to discuss my project with you.';

$whatsappUrl = 'https://wa.me/' . $whatsappNumber
    . '?text=' . rawurlencode($whatsappMessage);

?>

<style>
    .whatsapp-float {
        position: fixed;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 9999;

        width: 58px;
        height: 58px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #25D366;
        color: #ffffff;

        border-radius: 50%;
        text-decoration: none;

        box-shadow: 0 5px 20px rgba(37, 211, 102, 0.35);

        transition: all 0.3s ease;
    }

    .whatsapp-float:hover {
        background: #20ba5a;
        color: #ffffff;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.45);
    }

    .whatsapp-float svg {
        width: 32px;
        height: 32px;
        fill: currentColor;
    }

    .whatsapp-tooltip {
        position: absolute;
        right: 72px;
        top: 50%;
        transform: translateY(-50%);

        background: #ffffff;
        color: #222222;

        padding: 10px 15px;
        border-radius: 8px;

        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;

        opacity: 0;
        visibility: hidden;

        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .whatsapp-float:hover .whatsapp-tooltip {
        opacity: 1;
        visibility: visible;
    }

    @media (max-width: 576px) {
        .whatsapp-float {
            right: 15px;
            width: 52px;
            height: 52px;
        }

        .whatsapp-float svg {
            width: 29px;
            height: 29px;
        }

        .whatsapp-tooltip {
            display: none;
        }
    }
</style>

<a
    href="<?= htmlspecialchars($whatsappUrl, ENT_QUOTES, 'UTF-8'); ?>"
    class="whatsapp-float"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Chat with me on WhatsApp"
    title="Chat with me on WhatsApp"
>

    <span class="whatsapp-tooltip">
        Chat with me on WhatsApp
    </span>

    <svg
        viewBox="0 0 32 32"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        <path d="M16.01 3C8.84 3 3.01 8.82 3.01 15.99c0 2.29.6 4.53 1.75 6.51L3 29l6.68-1.74a12.96 12.96 0 0 0 6.32 1.62h.01c7.17 0 13-5.83 13-13 0-3.48-1.35-6.75-3.82-9.2A12.91 12.91 0 0 0 16.01 3zm0 23.68c-1.93 0-3.82-.52-5.47-1.5l-.39-.23-3.97 1.04 1.06-3.87-.25-.4a10.75 10.75 0 0 1-1.65-5.73c0-5.91 4.81-10.72 10.73-10.72 2.86 0 5.55 1.12 7.57 3.14a10.65 10.65 0 0 1 3.14 7.58c0 5.91-4.81 10.69-10.77 10.69zm5.89-8.02c-.32-.16-1.9-.94-2.19-1.05-.29-.1-.5-.16-.71.16-.21.32-.82 1.05-1.01 1.26-.18.21-.37.24-.69.08-.32-.16-1.35-.5-2.57-1.59-.95-.84-1.59-1.88-1.78-2.2-.18-.32-.02-.49.14-.65.14-.14.32-.37.48-.55.16-.18.21-.32.32-.53.1-.21.05-.4-.03-.56-.08-.16-.71-1.72-.98-2.35-.26-.62-.52-.54-.71-.55h-.61c-.21 0-.55.08-.84.4-.29.32-1.1 1.08-1.1 2.64 0 1.56 1.13 3.06 1.29 3.27.16.21 2.22 3.39 5.38 4.75.75.32 1.34.52 1.8.67.76.24 1.45.21 2 .13.61-.09 1.9-.77 2.17-1.51.26-.74.26-1.37.18-1.51-.08-.13-.29-.21-.61-.37z"/>
    </svg>

</a>
