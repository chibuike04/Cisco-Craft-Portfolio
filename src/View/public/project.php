<?php

$projects = (isset($projects) && is_array($projects)) ? $projects : [];

$projectLookup = [];

foreach ($projects as $project) {
    $projectLookup[$project['slug']] = $project;
}

$escape = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$getProject = static function (string $slug) use ($projectLookup): array {
    return $projectLookup[$slug] ?? [];
};

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Projects - Chibuikem Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --bg-dark: #0d0806;
            --card-bg: linear-gradient(155deg,
                    rgba(36, 22, 17, 0.76) 0%,
                    rgba(22, 14, 10, 0.72) 100%);
            --card-border: rgba(245, 158, 11, 0.16);
            --card-hover-border: rgba(245, 158, 11, 0.42);
            --primary: #e07a3f;
            --primary-gold: #f59e0b;
            --primary-hover: #c85e25;
            --primary-gradient: linear-gradient(135deg,
                    #f59e0b 0%,
                    #e07a3f 55%,
                    #c65f24 100%);
            --text-main: #fcf9f6;
            --text-muted: #a89c94;
            --text-dim: #71655e;
            --badge-bg: linear-gradient(135deg,
                    rgba(245, 158, 11, 0.12) 0%,
                    rgba(224, 122, 63, 0.06) 100%);
            --badge-border: rgba(245, 158, 11, 0.28);
            --badge-color: #fbbf24;
        }

        /* GLOBAL RESET */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family:
                "Inter",
                -apple-system,
                BlinkMacSystemFont,
                sans-serif;
            width: 100%;
            max-width: 100%;
            overflow-x: clip;
        }

        body {
            min-height: 100vh;
            width: 100%;
            max-width: 100%;
            background:
                radial-gradient(circle at 12% 15%,
                    rgba(224, 122, 63, 0.08) 0%,
                    transparent 45%),
                radial-gradient(circle at 88% 30%,
                    rgba(198, 95, 36, 0.07) 0%,
                    transparent 45%),
                radial-gradient(circle at 25% 75%,
                    rgba(224, 122, 63, 0.06) 0%,
                    transparent 40%),
                radial-gradient(circle at 80% 90%,
                    rgba(245, 158, 11, 0.05) 0%,
                    transparent 50%),
                linear-gradient(rgba(224, 122, 63, 0.018) 1px, transparent 1px),
                linear-gradient(90deg,
                    rgba(224, 122, 63, 0.018) 1px,
                    transparent 1px),
                #0d0806;
            background-size:
                100% 100%,
                100% 100%,
                100% 100%,
                100% 100%,
                48px 48px,
                48px 48px,
                100% 100%;
            overflow-x: clip;
            position: relative;
        }

        /* PREVENT GRID AND FLEX OVERFLOW */
        .container {
            width: 100%;
            max-width: 1160px;
            min-width: 0;
            margin: 0 auto;
            padding: 0 24px;
        }

        img,
        video,
        iframe,
        canvas,
        svg {
            max-width: 100%;
        }

        /* PILL BADGES */
        .pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--badge-color);
            background: var(--badge-bg);
            border: 1px solid var(--badge-border);
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 18px;
        }

        /* SECTION HEADINGS */
        .section-header-center {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            line-height: 1.2;
            position: relative;
            display: inline-block;
        }

        .section-title-underline {
            width: 44px;
            height: 3px;
            background: linear-gradient(90deg,
                    #fbbf24 0%,
                    #f59e0b 50%,
                    #e07a3f 100%);
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.4);
            border-radius: 2px;
            margin: 10px auto 0;
        }

        .gradient-alex {
            background: linear-gradient(135deg,
                    #fde68a 0%,
                    #fbbf24 30%,
                    #f59e0b 65%,
                    #d96225 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* AMBIENT GLOW EFFECTS */
        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(100px);
            z-index: 0;
        }

        .glow-point-1 {
            top: 60px;
            left: 15%;
            width: 540px;
            height: 540px;
            background: radial-gradient(circle,
                    rgba(245, 158, 11, 0.12) 0%,
                    rgba(224, 122, 63, 0.05) 55%,
                    transparent 75%);
        }

        .glow-point-2 {
            top: 650px;
            right: 5%;
            width: 580px;
            height: 580px;
            background: radial-gradient(circle,
                    rgba(224, 122, 63, 0.1) 0%,
                    rgba(245, 158, 11, 0.04) 50%,
                    transparent 70%);
        }

        .glow-point-3 {
            top: 1400px;
            left: -50px;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle,
                    rgba(245, 158, 11, 0.09) 0%,
                    rgba(224, 122, 63, 0.04) 55%,
                    transparent 75%);
        }

        .glow-point-4 {
            top: 2100px;
            right: 15%;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle,
                    rgba(245, 158, 11, 0.08) 0%,
                    rgba(217, 104, 38, 0.04) 50%,
                    transparent 70%);
        }

        /* NAVIGATION BAR */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(13, 8, 6, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(224, 122, 63, 0.12);
            transition: all 0.3s ease;
            width: 100%;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
            min-width: 0;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 19px;
            font-weight: 700;
            color: #ffffff;
        }

        .nav-logo-icon {
            color: var(--primary);
            font-family: "Fira Code", monospace;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
            position: relative;
            padding: 6px 0;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #ffffff;
        }

        .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        .btn-hire {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-gradient);
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            padding: 9px 20px;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px rgba(224, 122, 63, 0.35);
        }

        .btn-hire:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(224, 122, 63, 0.5);
        }

        /* BUTTONS */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-gradient);
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 22px;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 18px rgba(224, 122, 63, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(224, 122, 63, 0.5);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-main);
            font-size: 13.5px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 9999px;
            text-decoration: none;
            border: 1px solid rgba(224, 122, 63, 0.2);
            transition: all 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: rgba(224, 122, 63, 0.12);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        /* =========================================
   SECTION 1: PAGE HEADER & FILTER TABS
========================================= */

        .projects-hero {
            padding-top: 150px;
            padding-bottom: 50px;
            text-align: center;
            width: 100%;
            min-width: 0;
        }

        .projects-hero h1 {
            font-size: 52px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 18px;
            overflow-wrap: break-word;
        }

        .projects-hero-subtext {
            font-size: 17px;
            line-height: 1.65;
            color: var(--text-muted);
            max-width: 680px;
            margin: 0 auto 36px;
        }

        /* KEY STATS STRIP */
        .hero-stat-badges {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 13.5px;
            color: var(--text-main);
            backdrop-filter: blur(12px);
        }

        .stat-pill-num {
            color: var(--primary);
            font-weight: 700;
        }

        /* FILTER BUTTONS */
        .filter-tabs-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            max-width: 100%;
        }

        .filter-btn {
            background: rgba(28, 18, 14, 0.6);
            border: 1px solid var(--card-border);
            color: var(--text-muted);
            padding: 9px 20px;
            border-radius: 9999px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }

        .filter-btn:hover {
            color: #ffffff;
            border-color: rgba(224, 122, 63, 0.35);
        }

        .filter-btn.active {
            background: var(--primary-gradient);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 16px rgba(224, 122, 63, 0.35);
        }

        /* =========================================
   SECTION 2: PROJECTS SHOWCASE GRID
========================================= */

        .projects-grid-section {
            padding: 40px 0 100px;
            width: 100%;
            min-width: 0;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 26px;
            width: 100%;
            min-width: 0;
        }

        .project-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-width: 0;
            max-width: 100%;
            box-shadow:
                inset 0 1px 0 0 rgba(245, 158, 11, 0.18),
                0 20px 40px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .project-card:hover {
            border-color: var(--card-hover-border);
            transform: translateY(-5px);
            box-shadow:
                inset 0 1px 0 0 rgba(245, 158, 11, 0.35),
                0 24px 50px rgba(0, 0, 0, 0.6),
                0 0 25px rgba(245, 158, 11, 0.12);
        }

        .project-card.hidden {
            display: none !important;
        }

        .thumb-preview-box {
            width: 100%;
            height: 210px;
            position: relative;
            background: #140c09;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(224, 122, 63, 0.1);
        }

        .tag-number {
            position: absolute;
            top: 14px;
            left: 14px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            background: rgba(20, 12, 9, 0.8);
            backdrop-filter: blur(8px);
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid rgba(224, 122, 63, 0.2);
            z-index: 2;
        }

        .tag-category {
            position: absolute;
            top: 14px;
            right: 14px;
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            background: rgba(224, 122, 63, 0.12);
            backdrop-filter: blur(8px);
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid rgba(224, 122, 63, 0.3);
            z-index: 2;
        }

        .project-info-wrap {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            min-width: 0;
        }

        .card-project-title {
            font-size: 19px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
            overflow-wrap: anywhere;
        }

        .card-project-desc {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
            overflow-wrap: anywhere;
        }

        .tech-tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 22px;
            min-width: 0;
        }

        .tech-tag {
            font-size: 11.5px;
            font-weight: 600;
            color: #c9bcaf;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 4px 10px;
            border-radius: 6px;
        }

        .card-footer-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            border-top: 1px solid rgba(224, 122, 63, 0.1);
            padding-top: 16px;
        }

        .link-live {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .link-live:hover {
            color: #f59e0b;
            gap: 8px;
        }

        .link-github {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .link-github:hover {
            color: #ffffff;
        }

        /* =========================================
   SECTION 3: ARCHITECTURE & ENGINEERING
========================================= */

        .process-section {
            padding: 90px 0;
            border-top: 1px solid rgba(224, 122, 63, 0.12);
            width: 100%;
        }

        .process-4col-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 22px;
            min-width: 0;
        }

        .process-step-card {
            background: var(--card-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 28px 22px;
            position: relative;
            min-width: 0;
            box-shadow:
                inset 0 1px 0 0 rgba(245, 158, 11, 0.16),
                0 14px 30px rgba(0, 0, 0, 0.4);
            transition: all 0.25s ease;
        }

        .process-step-card:hover {
            border-color: var(--card-hover-border);
            box-shadow:
                inset 0 1px 0 0 rgba(245, 158, 11, 0.35),
                0 18px 36px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(245, 158, 11, 0.1);
            transform: translateY(-4px);
        }

        .step-number-watermark {
            font-family: "Fira Code", monospace;
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            background: var(--badge-bg);
            border: 1px solid var(--badge-border);
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .step-card-title {
            font-size: 17px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .step-card-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* =========================================
   SECTION 4: TECH MATRIX & OPEN SOURCE
========================================= */

        .impact-section {
            padding: 90px 0 110px;
            border-top: 1px solid rgba(224, 122, 63, 0.12);
            width: 100%;
        }

        .impact-3col-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1.25fr);
            gap: 24px;
            align-items: stretch;
            min-width: 0;
        }

        .impact-box {
            background: var(--card-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            box-shadow:
                inset 0 1px 0 0 rgba(245, 158, 11, 0.16),
                0 14px 30px rgba(0, 0, 0, 0.4);
            transition: all 0.25s ease;
        }

        .impact-box:hover {
            border-color: var(--card-hover-border);
            box-shadow:
                inset 0 1px 0 0 rgba(245, 158, 11, 0.35),
                0 18px 36px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(245, 158, 11, 0.1);
        }

        .impact-box-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .impact-box-title svg {
            color: var(--primary);
        }

        .impact-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .impact-list-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .impact-list-item span.bullet {
            color: var(--primary);
            font-weight: 700;
        }

        /* CTA BANNER */
        .cta-banner-card {
            background: linear-gradient(135deg,
                    rgba(245, 158, 11, 0.16) 0%,
                    rgba(224, 122, 63, 0.08) 50%,
                    rgba(28, 18, 14, 0.95) 100%);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 18px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
            justify-content: space-between;
            min-width: 0;
        }

        .cta-banner-title {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 12px;
            line-height: 1.25;
        }

        .cta-banner-desc {
            font-size: 14px;
            color: #dfd4cc;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        /* =========================================
   FOOTER
========================================= */

        .contact-section {
            padding: 90px 0 35px;
            border-top: 1px solid rgba(224, 122, 63, 0.12);
            width: 100%;
            min-width: 0;
        }

        .contact-3col-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 1.35fr) minmax(0,
                    1fr);
            gap: 40px;
            margin-bottom: 45px;
            min-width: 0;
        }

        .contact-cta h2 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 14px;
            line-height: 1.25;
        }

        .contact-cta p {
            font-size: 14.5px;
            line-height: 1.65;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .testimonial-card {
            background: var(--card-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
        }

        .testimonial-card:hover {
            box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.35), 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 20px rgba(245, 158, 11, 0.12);
        }

        .testimonial-quote-icon {
            color: var(--primary);
            font-size: 32px;
            line-height: 1;
            margin-bottom: 8px;
            font-family: Georgia, serif;
        }

        .testimonial-text {
            font-size: 14px;
            line-height: 1.65;
            color: #ede5df;
            margin-bottom: 20px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid rgba(224, 122, 63, 0.4);
            flex-shrink: 0;
        }

        .author-name {
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
        }

        .author-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        .follow-col-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .social-icons-row {
            display: flex;
            gap: 12px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(28, 18, 14, 0.6);
            border: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .social-btn:hover {
            color: #ffffff;
            border-color: var(--primary);
            background: rgba(224, 122, 63, 0.16);
            transform: translateY(-2px);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: var(--text-muted);
            margin-bottom: 12px;
            text-decoration: none;
            transition: color 0.2s ease;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .contact-item:hover {
            color: #ffffff;
        }

        .contact-item svg {
            color: var(--primary);
            flex-shrink: 0;
        }

        /* SUB FOOTER */
        .sub-footer {
            border-top: 1px solid rgba(224, 122, 63, 0.1);
            padding-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            font-size: 13px;
            color: var(--text-dim);
            min-width: 0;
        }

        /* =========================================
   TABLET RESPONSIVENESS
========================================= */

        @media (max-width: 968px) {

            .hero-grid,
            .about-grid,
            .contact-3col-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 30px;
            }

            .skills-3col-grid,
            .projects-3col-grid,
            .projects-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .process-4col-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .impact-3col-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .impact-3col-grid .cta-banner-card {
                grid-column: 1 / -1;
            }

            .projects-hero h1 {
                font-size: 42px;
            }

            .nav-links {
                display: none;
            }
        }

        /* =========================================
   MOBILE RESPONSIVENESS
========================================= */

        @media (max-width: 600px) {

            html,
            body {
                width: 100%;
                max-width: 100%;
                overflow-x: clip;
            }

            body {
                min-height: 100vh;
            }

            .container {
                width: 100%;
                max-width: 100%;
                padding-left: 20px;
                padding-right: 20px;
                margin-left: auto;
                margin-right: auto;
            }

            /* CONTAIN BACKGROUND GLOWS */
            .ambient-glow {
                max-width: 100%;
                filter: blur(65px);
                opacity: 0.7;
            }

            .glow-point-1 {
                left: 0;
                width: 300px;
                height: 300px;
            }

            .glow-point-2 {
                right: 0;
                width: 300px;
                height: 300px;
            }

            .glow-point-3 {
                left: 0;
                width: 280px;
                height: 280px;
            }

            .glow-point-4 {
                right: 0;
                width: 300px;
                height: 300px;
            }

            /* NAVIGATION */
            .nav-inner {
                height: 64px;
            }

            .nav-logo {
                font-size: 17px;
            }

            .btn-hire {
                padding: 8px 14px;
                font-size: 12px;
            }

            /* PROJECT HEADER */
            .projects-hero {
                padding-top: 115px;
                padding-bottom: 30px;
            }

            .projects-hero h1 {
                font-size: clamp(30px, 9vw, 38px);
                letter-spacing: -0.8px;
                line-height: 1.15;
                margin-bottom: 14px;
                overflow-wrap: anywhere;
            }

            .projects-hero-subtext {
                font-size: 14px;
                line-height: 1.7;
                margin-bottom: 25px;
                padding: 0;
            }

            .hero-stat-badges {
                gap: 10px;
                margin-bottom: 28px;
            }

            .stat-pill {
                font-size: 11px;
                padding: 7px 12px;
            }

            /* FILTER BUTTONS */
            .filter-tabs-wrap {
                gap: 8px;
                width: 100%;
            }

            .filter-btn {
                padding: 8px 13px;
                font-size: 11.5px;
            }

            /* PROJECT GRID */
            .projects-grid-section {
                padding: 25px 0 55px;
            }

            .projects-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 22px;
                width: 100%;
            }

            .project-card {
                width: 100%;
                max-width: 100%;
                min-width: 0;
            }

            .thumb-preview-box {
                width: 100%;
                height: 200px;
            }

            .project-info-wrap {
                padding: 20px;
            }

            .card-project-title {
                font-size: 18px;
            }

            .card-project-desc {
                font-size: 13px;
            }

            .card-footer-actions {
                gap: 10px;
            }

            /* PROCESS SECTION */
            .process-section {
                padding: 55px 0;
            }

            .process-4col-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 16px;
            }

            .process-step-card {
                padding: 23px 20px;
            }

            /* IMPACT SECTION */
            .impact-section {
                padding: 55px 0 60px;
            }

            .impact-3col-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 18px;
            }

            .impact-3col-grid .cta-banner-card {
                grid-column: auto;
            }

            .impact-box {
                padding: 23px 20px;
            }

            .cta-banner-card {
                padding: 24px 20px;
            }

            .cta-banner-title {
                font-size: 21px;
            }

            /* FOOTER */
            .contact-section {
                padding: 50px 0 18px;
                margin-bottom: 0;
            }

            .contact-3col-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 26px;
                margin-bottom: 28px;
            }

            .contact-cta h2 {
                font-size: 26px;
            }

            .contact-cta p {
                font-size: 13.5px;
                margin-bottom: 18px;
            }

            .testimonial-card {
                padding: 21px;
            }

            .testimonial-text {
                font-size: 13px;
                line-height: 1.65;
                margin-bottom: 18px;
            }

            .follow-col-title {
                margin-bottom: 14px;
            }

            .social-icons-row {
                gap: 10px;
                margin-bottom: 18px;
            }

            .contact-item {
                font-size: 12px;
                line-height: 1.5;
                overflow-wrap: anywhere;
            }

            /* MOBILE SUB FOOTER */
            .sub-footer {
                padding-top: 18px;
                padding-bottom: 5px;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: flex-start;
                gap: 8px;
                font-size: 11.5px;
                line-height: 1.5;
            }
        }

        /* =========================================
   EXTRA SMALL PHONES
========================================= */

        @media (max-width: 380px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }

            .projects-hero h1 {
                font-size: 30px;
            }

            .projects-hero-subtext {
                font-size: 13px;
            }

            .filter-btn {
                padding: 7px 10px;
                font-size: 10.5px;
            }

            .project-info-wrap {
                padding: 17px;
            }

            .impact-box,
            .cta-banner-card {
                padding: 19px;
            }

            .contact-item {
                font-size: 11px;
            }
        }

        /* =========================================
   FINAL OVERFLOW PROTECTION
========================================= */

        @media (max-width: 768px) {

            .projects-grid,
            .process-4col-grid,
            .impact-3col-grid,
            .contact-3col-grid,
            .container,
            .projects-hero,
            .projects-grid-section,
            .process-section,
            .impact-section,
            .contact-section {
                min-width: 0;
                max-width: 100%;
            }

            .project-card,
            .process-step-card,
            .impact-box,
            .testimonial-card,
            .cta-banner-card {
                min-width: 0;
                max-width: 100%;
            }

            /* Prevent long text and links from expanding the page */
            p,
            h1,
            h2,
            h3,
            a,
            span {
                overflow-wrap: anywhere;
            }

            /* Keep the page within the device viewport */
            html,
            body {
                overflow-x: clip;
            }
        }
    </style>
</head>

<body>
    <!-- Ambient Gold-Orange Glow Points (Low Opacity) -->
    <div class="ambient-glow glow-point-1"></div>
    <div class="ambient-glow glow-point-2"></div>
    <div class="ambient-glow glow-point-3"></div>
    <div class="ambient-glow glow-point-4"></div>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="container nav-inner">
            <a href="<?= e(url('/')) ?>" class="nav-logo">
                <span class="nav-logo-icon">&lt;/&gt;</span>
                <span>CiscoCraft</span>
            </a>
            <nav>
                <ul class="nav-links">
                    <li><a href="<?= e(url('/')) ?>" class="nav-link active">Home</a></li>
                    <li><a href="<?= e(url('/#about')) ?>" class="nav-link">About</a></li>
                    <li><a href="<?= e(url('/#skills')) ?>" class="nav-link">Skills</a></li>
                    <li><a href="<?= e(url('/projects')) ?>" class="nav-link">Projects</a></li>
                    <li><a href="<?= e(url('/blog')) ?>" class="nav-link">Blog</a></li>
                    <li><a href="<?= e(url('/contact')) ?>" class="nav-link">Contact</a></li>
                </ul>
            </nav>
            <a href="<?= e(url('/contact')) ?>" class="btn-hire">
                Hire Me
                <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5">
                    <path d="M7 17L17 7M17 7H7M17 7V17" />
                </svg>
            </a>
        </div>
    </header>

    <main>
        <!-- =========================================
         SECTION 1: HERO / PAGE HEADER WITH TITLE & SUBTEXT
    ========================================= -->
        <section class="projects-hero">
            <div class="container">
                <div class="pill-badge">ALL WORKS &amp; CASE STUDIES</div>
                <h1>
                    Crafted with Purpose &amp; <span class="gradient-alex">Code</span>
                </h1>
                <p class="projects-hero-subtext">
                    A comprehensive gallery of digital products, full-stack
                    architectures, and modern web applications engineered for speed,
                    clean aesthetics, and exceptional user experience.
                </p>

                <!-- Stats Strip -->
                <div class="hero-stat-badges">
                    <div class="stat-pill">
                        <span class="stat-pill-num">12+</span> Production Apps
                    </div>
                    <div class="stat-pill">
                        <span class="stat-pill-num">99.9%</span> Lighthouse Performance
                    </div>
                    <div class="stat-pill">
                        <span class="stat-pill-num">100%</span> Client Satisfaction
                    </div>
                    <div class="stat-pill">
                        <span class="stat-pill-num">1,400+</span> Git Commits
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="filter-tabs-wrap">
                    <button class="filter-btn active" data-filter="all">
                        All Projects
                    </button>
                    <button class="filter-btn" data-filter="fullstack">
                        Full-Stack
                    </button>
                    <button class="filter-btn" data-filter="frontend">
                        Frontend / UI
                    </button>
                    <button class="filter-btn" data-filter="web3">
                        Fintech &amp; Web3
                    </button>
                    <button class="filter-btn" data-filter="ai">AI &amp; Tools</button>
                </div>
            </div>
        </section>

        <!-- =========================================
         SECTION 2: ALL PROJECTS SHOWCASE (6 DETAILED CARDS)
    ========================================= -->
        
<!-- =========================================
     SECTION 2: ALL PROJECTS SHOWCASE
========================================= -->

<section class="projects-grid-section">
    <div class="container">

        <div class="projects-grid" id="projectsContainer">

            <?php if (!empty($projects)): ?>

                <?php foreach ($projects as $index => $project): ?>

                    <?php
                    $projectTitle = (string) ($project['title'] ?? 'Untitled Project');

                    $categoryName = (string) (
                        $project['category_name'] ?? 'Uncategorized'
                    );

                    $categorySlug = strtolower(trim(
                        (string) ($project['category_slug'] ?? '')
                    ));

                    // Match database categories to the existing filter buttons.
                    $categoryFilter = match (strtolower(trim($categoryName))) {
                        'full-stack' => 'fullstack',
                        'frontend / ui' => 'frontend',
                        'fintech & web3' => 'web3',
                        'ai & tools' => 'ai',
                        default => preg_replace(
                            '/[^a-z0-9]+/i',
                            '',
                            $categorySlug ?: $categoryName
                        ),
                    };

                    $projectSummary = trim(
                        (string) ($project['summary'] ?? '')
                    );

                    if ($projectSummary === '') {
                        $projectSummary = trim(
                            (string) ($project['description'] ?? '')
                        );
                    }

                    $thumbnailUrl = trim(
                        (string) ($project['thumbnail_url'] ?? '')
                    );

                    $liveUrl = trim(
                        (string) ($project['project_url'] ?? '')
                    );

                    $repositoryUrl = trim(
                        (string) ($project['repository_url'] ?? '')
                    );

                    // Only allow valid HTTP/HTTPS external links.
                    if (
                        !filter_var($liveUrl, FILTER_VALIDATE_URL) ||
                        !in_array(
                            strtolower((string) parse_url($liveUrl, PHP_URL_SCHEME)),
                            ['http', 'https'],
                            true
                        )
                    ) {
                        $liveUrl = '';
                    }

                    if (
                        !filter_var($repositoryUrl, FILTER_VALIDATE_URL) ||
                        !in_array(
                            strtolower((string) parse_url($repositoryUrl, PHP_URL_SCHEME)),
                            ['http', 'https'],
                            true
                        )
                    ) {
                        $repositoryUrl = '';
                    }

                    $projectNumber = str_pad(
                        (string) ($index + 1),
                        2,
                        '0',
                        STR_PAD_LEFT
                    );

                    $escape = static fn($value): string =>
                        htmlspecialchars(
                            (string) $value,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                    $projectTechnologies = $project['technologies'] ?? [];
                    ?>

                    <div
                        class="project-card"
                        data-category="<?= $escape($categoryFilter) ?>"
                    >

                        <div class="thumb-preview-box">

                            <span class="tag-number">
                                <?= $escape($projectNumber) ?>
                            </span>

                            <span class="tag-category">
                                <?= $escape($categoryName) ?>
                            </span>

                            <?php if ($thumbnailUrl !== ''): ?>

                                <img
                                    src="<?= $escape($thumbnailUrl) ?>"
                                    alt="<?= $escape($projectTitle) ?>"
                                    loading="lazy"
                                    style="width:100%;height:100%;object-fit:cover;"
                                >

                            <?php else: ?>

                                <!-- Default thumbnail when no project image exists -->
                                <svg
                                    width="100%"
                                    height="100%"
                                    viewBox="0 0 320 210"
                                    preserveAspectRatio="none"
                                    aria-hidden="true"
                                >
                                    <defs>
                                        <linearGradient
                                            id="projectGradient<?= (int) $index ?>"
                                            x1="0%"
                                            y1="0%"
                                            x2="100%"
                                            y2="100%"
                                        >
                                            <stop offset="0%" stop-color="#24140e" />
                                            <stop offset="100%" stop-color="#100906" />
                                        </linearGradient>
                                    </defs>

                                    <rect
                                        width="320"
                                        height="210"
                                        fill="url(#projectGradient<?= (int) $index ?>)"
                                    />

                                    <rect
                                        x="25"
                                        y="30"
                                        width="270"
                                        height="150"
                                        rx="10"
                                        fill="#180e0a"
                                        stroke="#5c3626"
                                        stroke-width="1"
                                    />

                                    <circle
                                        cx="48"
                                        cy="52"
                                        r="5"
                                        fill="#e07a3f"
                                    />

                                    <circle
                                        cx="65"
                                        cy="52"
                                        r="5"
                                        fill="#f59e0b"
                                    />

                                    <circle
                                        cx="82"
                                        cy="52"
                                        r="5"
                                        fill="#10b981"
                                    />

                                    <rect
                                        x="45"
                                        y="75"
                                        width="105"
                                        height="7"
                                        rx="3"
                                        fill="#e07a3f"
                                    />

                                    <rect
                                        x="45"
                                        y="94"
                                        width="180"
                                        height="5"
                                        rx="2"
                                        fill="#5c3626"
                                    />

                                    <rect
                                        x="45"
                                        y="108"
                                        width="145"
                                        height="5"
                                        rx="2"
                                        fill="#5c3626"
                                    />

                                    <rect
                                        x="45"
                                        y="130"
                                        width="75"
                                        height="25"
                                        rx="5"
                                        fill="#d96826"
                                    />

                                    <rect
                                        x="205"
                                        y="75"
                                        width="65"
                                        height="65"
                                        rx="8"
                                        fill="#24140e"
                                        stroke="#e07a3f"
                                        stroke-width="2"
                                    />

                                    <path
                                        d="M220 120 L235 100 L248 112 L258 90"
                                        fill="none"
                                        stroke="#f59e0b"
                                        stroke-width="3"
                                        stroke-linecap="round"
                                    />
                                </svg>

                            <?php endif; ?>

                        </div>

                        <div class="project-info-wrap">

                            <h3 class="card-project-title">
                                <?= $escape($projectTitle) ?>
                            </h3>

                            <p class="card-project-desc">
                                <?= $escape($projectSummary) ?>
                            </p>

                            <div class="tech-tags-list">

                                <?php if (!empty($projectTechnologies)): ?>

                                    <?php foreach ($projectTechnologies as $technology): ?>

                                        <?php
                                        $technologyName = is_array($technology)
                                            ? ($technology['name'] ?? '')
                                            : $technology;
                                        ?>

                                        <?php if (trim((string) $technologyName) !== ''): ?>
                                            <span class="tech-tag">
                                                <?= $escape($technologyName) ?>
                                            </span>
                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </div>

                            <?php if ($liveUrl !== '' || $repositoryUrl !== ''): ?>

                                <div class="card-footer-actions">

                                    <?php if ($liveUrl !== ''): ?>

                                        <a
                                            href="<?= $escape($liveUrl) ?>"
                                            class="link-live"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Live Demo

                                            <svg
                                                width="14"
                                                height="14"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2.5"
                                            >
                                                <path d="M7 17L17 7M17 7H7M17 7V17" />
                                            </svg>
                                        </a>

                                    <?php endif; ?>

                                    <?php if ($repositoryUrl !== ''): ?>

                                        <a
                                            href="<?= $escape($repositoryUrl) ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="link-github"
                                        >
                                            <svg
                                                width="15"
                                                height="15"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"
                                                />
                                            </svg>

                                            GitHub
                                        </a>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="no-projects">
                    <p>No projects are available at the moment.</p>
                </div>

            <?php endif; ?>

        </div>

    </div>
</section>


        <!-- =========================================
         SECTION 3: HOW I BUILD / ARCHITECTURE PROCESS
    ========================================= -->
        <section class="process-section">
            <div class="container">
                <div class="section-header-center">
                    <div class="pill-badge">DEVELOPMENT LIFECYCLE</div>
                    <h2 class="section-title">How I Build &amp; Ship</h2>
                    <div class="section-title-underline"></div>
                </div>

                <div class="process-4col-grid">
                    <!-- Step 1 -->
                    <div class="process-step-card">
                        <span class="step-number-watermark">PHASE 01</span>
                        <h3 class="step-card-title">Discovery &amp; Architecture</h3>
                        <p class="step-card-desc">
                            Mapping data models, edge cases, and design systems in Figma
                            before touching code to eliminate architectural debt early.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="process-step-card">
                        <span class="step-number-watermark">PHASE 02</span>
                        <h3 class="step-card-title">Strict Type Safety</h3>
                        <p class="step-card-desc">
                            End-to-end typed contracts between client and database using
                            TypeScript, Zod validation, and clean component isolation.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="process-step-card">
                        <span class="step-number-watermark">PHASE 03</span>
                        <h3 class="step-card-title">Speed &amp; Accessibility</h3>
                        <p class="step-card-desc">
                            Audited for 99+ Lighthouse scores, sub-second TTFB, image
                            compression, fluid 120fps animations, and WCAG AA standards.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="process-step-card">
                        <span class="step-number-watermark">PHASE 04</span>
                        <h3 class="step-card-title">CI/CD &amp; Monitoring</h3>
                        <p class="step-card-desc">
                            Automated unit and integration pipelines, preview environments,
                            multi-region CDN caching, and telemetry alerts.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- =========================================
         SECTION 4: TECH MATRIX & OPEN SOURCE IMPACT
    ========================================= -->
        <section class="impact-section">
            <div class="container">
                <div class="section-header-center">
                    <div class="pill-badge">CORE COMPETENCIES</div>
                    <h2 class="section-title">Ecosystem &amp; Tech Matrix</h2>
                    <div class="section-title-underline"></div>
                </div>

                <div class="impact-3col-grid">
                    <!-- Column 1: Frontend & Frameworks -->
                    <div class="impact-box">
                        <div>
                            <h3 class="impact-box-title">
                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                    <polyline points="2 17 12 22 22 17" />
                                    <polyline points="2 12 12 17 22 12" />
                                </svg>
                                Frontend &amp; Architecture
                            </h3>
                            <ul class="impact-list">
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> Next.js 14 App Router, Server
                                    Components &amp; ISR
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> React 18, Concurrent Mode
                                    &amp; Custom Hooks
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> TypeScript with strict
                                    zero-any policies
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> Tailwind CSS, CSS Modules
                                    &amp; Responsive UI
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> Three.js, Canvas &amp; WebGL
                                    interactive visualizers
                                </li>
                            </ul>
                        </div>
                        <div style="margin-top: 24px; font-size: 12px; color: var(--primary); font-family: 'Fira Code', monospace;">
                            // Built for sub-second FID &amp; LCP
                        </div>

                    </div>

                    <!-- Column 2: Backend & Infrastructure -->
                    <div class="impact-box">
                        <div>
                            <h3 class="impact-box-title">
                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="2" y="2" width="20" height="8" rx="2" ry="2" />
                                    <rect x="2" y="14" width="20" height="8" rx="2" ry="2" />
                                    <line x1="6" y1="6" x2="6.01" y2="6" />
                                    <line x1="6" y1="18" x2="6.01" y2="18" />
                                </svg>
                                Backend &amp; Infrastructure
                            </h3>
                            <ul class="impact-list">
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> Node.js, Express &amp; REST /
                                    GraphQL endpoints
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> PostgreSQL, ClickHouse &amp;
                                    Redis caching
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> Supabase, Prisma ORM &amp;
                                    Row-Level Security
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> Docker containerization &amp;
                                    automated CI/CD
                                </li>
                                <li class="impact-list-item">
                                    <span class="bullet">▹</span> Vercel Edge Runtime &amp;
                                    Cloudflare Workers
                                </li>
                            </ul>
                        </div>
                        <div style="margin-top: 24px; font-size: 12px; color: var(--primary); font-family: 'Fira Code', monospace;">
                            // 99.9% uptime architecture
                        </div>

                    </div>

                    <!-- Column 3: Ready to Build Callout Banner -->
                    <div class="cta-banner-card">
                        <div>
                            <div
                                class="pill-badge"
                                style="
                    background: rgba(224, 122, 63, 0.2);
                    border-color: rgba(224, 122, 63, 0.4);
                  ">
                                OPEN FOR CONTRACTS
                            </div>
                            <h3 class="cta-banner-title">Want to stay updated with my latest insights?</h3>
                            <p class="cta-banner-desc">
                              Whether you're looking for deep-dive technical tutorials, project case studies, or thoughts on the future of web development, check out my latest write-ups and industry insights.
                            </p>
                        </div>
                        <div>
                            <a
                                href="<?= e(url('/blog')) ?>"
                                class="btn-primary"
                                style="width: 100%; justify-content: center">
                                Read the Blog
                                <svg
                                    width="15"
                                    height="15"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M7 17L17 7M17 7H7M17 7V17" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =========================================
         FOOTER / CONTACT SECTION
    ========================================= -->
        <section class="contact-section">
            <div class="container">
                <div class="contact-3col-grid">
                    <!-- Left: Let's Work Together -->
                    <div class="contact-cta">
                        <div class="pill-badge">LET'S WORK TOGETHER</div>
                        <h2>Have a project in mind?</h2>
                        <p>
                            I'm always open to discussing new projects, technical
                            consulting, and development contracts.
                        </p>
                        <a href="<?= e(url('/contact')) ?>" class="btn-primary">
                            Get in Touch
                            <svg
                                width="15"
                                height="15"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M7 17L17 7M17 7H7M17 7V17" />
                            </svg>
                        </a>
                    </div>

                    <!-- Middle: Testimonial Card -->
                    <div class="testimonial-card">
                        <div>
                            <div class="testimonial-quote-icon">“</div>
                            <p class="testimonial-text">
                                "Chibuikem is an exceptional developer who delivers
                                high-quality work on time. His attention to detail and
                                problem-solving skills are outstanding."
                            </p>
                        </div>
                        <div class="testimonial-author">
                            <svg
                                class="author-avatar"
                                viewBox="0 0 40 40"
                                width="40"
                                height="40">
                                <circle cx="20" cy="20" r="20" fill="#2d170e" />
                                <circle cx="20" cy="15" r="7" fill="#f5d0b8" />
                                <path
                                    d="M6 35c0-7.7 6.3-14 14-14s14 6.3 14 14"
                                    fill="#e07a3f" />
                            </svg>
                            <div>
                                <div class="author-name">Sarah Johnson</div>
                                <div class="author-role">CEO, TechStart</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Follow Me & Direct Contact -->
                    <div>
                        <div class="follow-col-title">FOLLOW ME</div>
                        <div class="social-icons-row">
                            <a
                                href="https://github.com"
                                target="_blank"
                                rel="noreferrer"
                                class="social-btn"
                                title="GitHub">
                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z" />
                                </svg>
                            </a>
                            <a
                                href="https://linkedin.com"
                                target="_blank"
                                rel="noreferrer"
                                class="social-btn"
                                title="LinkedIn">
                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.6a1.64 1.64 0 0 0-1.64 1.64c0 .91.73 1.64 1.64 1.64s1.64-.73 1.64-1.64A1.64 1.64 0 0 0 7.83 6.6z" />
                                </svg>
                            </a>
                            <a
                                href="https://twitter.com"
                                target="_blank"
                                rel="noreferrer"
                                class="social-btn"
                                title="Twitter/X">
                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                            <a
                                href="https://instagram.com"
                                target="_blank"
                                rel="noreferrer"
                                class="social-btn"
                                title="Instagram">
                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                        </div>

                        <div>
                            <a href="mailto:hello@alexdev.com" class="contact-item">
                                <svg
                                    width="17"
                                    height="17"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                                <span>chibuikemnweze2020@gmail.com</span>
                            </a>
                            <a href="tel:+15551234587" class="contact-item">
                                <svg
                                    width="17"
                                    height="17"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                                <span>+234 9161426400</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="sub-footer">
                    <div>&copy; 2024 CiscoCraftDeveloper. All rights reserved.</div>
                    <div>Made with ❤️ by CiscoCraft
                    </div>
                </div>
        </section>
    </main>

    <!-- Interactive Filter Tabs Script -->
    <script>
        (function() {
            const filterBtns = document.querySelectorAll(".filter-btn");
            const cards = document.querySelectorAll(
                "#projectsContainer .project-card",
            );

            filterBtns.forEach((btn) => {
                btn.addEventListener("click", () => {
                    filterBtns.forEach((b) => b.classList.remove("active"));
                    btn.classList.add("active");

                    const category = btn.getAttribute("data-filter");

                    cards.forEach((card) => {
                        const cardCat = card.getAttribute("data-category");
                        if (category === "all" || cardCat === category) {
                            card.classList.remove("hidden");
                        } else {
                            card.classList.add("hidden");
                        }
                    });
                });
            });
        })();
    </script>
</body>

</html>