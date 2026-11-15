<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="/assets/images/favicon.png">
  <title>Chibuikem - Web Developer Portfolio</title>
 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-dark: #0d0806;
      --card-bg: linear-gradient(155deg, rgba(36, 22, 17, 0.76) 0%, rgba(22, 14, 10, 0.72) 100%);
      --card-border: rgba(245, 158, 11, 0.16);
      --card-hover-border: rgba(245, 158, 11, 0.42);
      --primary: #e07a3f;
      --primary-gold: #f59e0b;
      --primary-hover: #c85e25;
      --primary-gradient: linear-gradient(135deg, #f59e0b 0%, #e07a3f 55%, #c65f24 100%);
      --text-main: #fcf9f6;
      --text-muted: #a89c94;
      --text-dim: #71655e;
      --badge-bg: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(224, 122, 63, 0.06) 100%);
      --badge-border: rgba(245, 158, 11, 0.28);
      --badge-color: #fbbf24;
    }

    /* GLOBAL RESET */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
      background-color: var(--bg-dark);
      color: var(--text-main);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      width: 100%;
      max-width: 100%;
      overflow-x: clip;
    }

    body {
      min-height: 100vh;
      width: 100%;
      max-width: 100%;
      overflow-x: clip;
      position: relative;
    }

    img,
    video,
    iframe {
      max-width: 100%;
    }

    /* FIXED CANVAS ANIMATION BACKGROUND */
    #canvas-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      z-index: 0;
      pointer-events: none;
      overflow: hidden;
    }

    canvas {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .bg-ambient-overlay {
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 50% 40%, rgba(13, 8, 6, 0.42) 0%, rgba(13, 8, 6, 0.82) 100%);
      pointer-events: none;
      z-index: 1;
    }

    /* MAIN CONTENT */
    .site-wrapper {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 100%;
      overflow-x: clip;
    }

    .container {
      width: 100%;
      max-width: 1160px;
      margin: 0 auto;
      padding: 0 24px;
      min-width: 0;
    }

    /* BADGES */
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
      margin-bottom: 48px;
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
      background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #e07a3f 100%);
      box-shadow: 0 0 12px rgba(245, 158, 11, 0.4);
      border-radius: 2px;
      margin: 10px auto 0;
    }

    /* AMBIENT GLOWS */
    .ambient-glow {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(100px);
      z-index: 1;
    }

    .glow-point-1 {
      top: 80px;
      left: -80px;
      width: 520px;
      height: 520px;
      background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, rgba(224, 122, 63, 0.05) 55%, transparent 75%);
    }

    .glow-point-2 {
      top: 240px;
      right: -60px;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(224, 122, 63, 0.11) 0%, rgba(245, 158, 11, 0.05) 50%, transparent 70%);
    }

    .glow-point-3 {
      top: 1050px;
      left: 30%;
      width: 560px;
      height: 560px;
      background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, rgba(224, 122, 63, 0.04) 55%, transparent 75%);
    }

    .glow-point-4 {
      top: 1750px;
      right: 10%;
      width: 540px;
      height: 540px;
      background: radial-gradient(circle, rgba(245, 158, 11, 0.09) 0%, rgba(217, 104, 38, 0.04) 50%, transparent 70%);
    }

    .glow-point-5 {
      top: 2500px;
      left: 5%;
      width: 580px;
      height: 580px;
      background: radial-gradient(circle, rgba(224, 122, 63, 0.09) 0%, rgba(245, 158, 11, 0.04) 55%, transparent 75%);
    }

    /* NAVIGATION */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      background: rgba(13, 8, 6, 0.75);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(224, 122, 63, 0.1);
      transition: all 0.3s ease;
    }

    .nav-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 72px;
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
      font-family: 'Fira Code', monospace;
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
      content: '';
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
      font-size: 14.5px;
      font-weight: 600;
      padding: 12px 24px;
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
      gap: 8px;
      background: rgba(255, 255, 255, 0.04);
      color: #ffffff;
      font-size: 14.5px;
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 9999px;
      text-decoration: none;
      border: 1px solid rgba(255, 255, 255, 0.14);
      transition: all 0.2s ease;
      backdrop-filter: blur(10px);
    }

    .btn-outline:hover {
      background: rgba(255, 255, 255, 0.08);
      border-color: rgba(255, 255, 255, 0.28);
      transform: translateY(-2px);
    }

    /* HERO */
    .hero-section {
      padding-top: 160px;
      padding-bottom: 120px;
      min-height: 85vh;
      display: flex;
      align-items: center;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1.25fr 0.95fr;
      gap: 50px;
      align-items: center;
      width: 100%;
      min-width: 0;
    }

    .hero-title {
      font-size: 54px;
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -1px;
      margin-bottom: 20px;
    }

    .gradient-alex {
      background: linear-gradient(135deg, #fde68a 0%, #fbbf24 30%, #f59e0b 65%, #d96225 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-desc {
      font-size: 16.5px;
      line-height: 1.65;
      color: var(--text-muted);
      margin-bottom: 32px;
      max-width: 500px;
    }

    .hero-cta-group {
      display: flex;
      gap: 16px;
      align-items: center;
      margin-bottom: 46px;
    }

    .tech-strip-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.5px;
      color: var(--text-dim);
      text-transform: uppercase;
      margin-bottom: 14px;
    }

    .tech-icons-row {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .tech-badge-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: rgba(28, 18, 14, 0.6);
      border: 1px solid var(--card-border);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      backdrop-filter: blur(8px);
    }

    .tech-badge-icon:hover {
      transform: translateY(-3px);
      border-color: var(--primary);
      background: rgba(224, 122, 63, 0.15);
    }

    /* CODE CARD */
    .code-preview-card {
      background: var(--card-bg);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.22), 0 20px 50px rgba(0, 0, 0, 0.55), 0 0 35px rgba(245, 158, 11, 0.08);
      transition: all 0.3s ease;
      min-width: 0;
    }

    .code-preview-card:hover {
      border-color: var(--card-hover-border);
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.4), 0 24px 55px rgba(0, 0, 0, 0.6), 0 0 30px rgba(245, 158, 11, 0.15);
    }

    .code-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 18px;
      background: rgba(255, 255, 255, 0.02);
      border-bottom: 1px solid rgba(224, 122, 63, 0.1);
    }

    .code-card-title {
      font-family: 'Fira Code', monospace;
      font-size: 12px;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .status-dot-amber {
      width: 8px;
      height: 8px;
      background: #e07a3f;
      border-radius: 50%;
      box-shadow: 0 0 8px #e07a3f;
    }

    .code-card-body {
      padding: 22px 24px;
      font-family: 'Fira Code', monospace;
      font-size: 13.5px;
      line-height: 1.8;
      overflow-x: auto;
    }

    .code-kw {
      color: #e27d42;
    }

    .code-var {
      color: #fcf9f6;
    }

    .code-prop {
      color: #c4b5ab;
    }

    .code-str {
      color: #f59e0b;
    }

    .code-punct {
      color: #786b63;
    }

    /* ABOUT */
    .about-section {
      padding: 100px 0;
    }

    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 50px;
      align-items: center;
      min-width: 0;
    }

    .about-text h2 {
      font-size: 38px;
      font-weight: 800;
      letter-spacing: -0.5px;
      line-height: 1.2;
      margin-bottom: 20px;
    }

    .about-text p {
      font-size: 16px;
      line-height: 1.7;
      color: var(--text-muted);
      margin-bottom: 32px;
      max-width: 480px;
    }

    .stats-2x2-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .stat-card {
      background: var(--card-bg);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      padding: 26px;
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.16), 0 16px 36px rgba(0, 0, 0, 0.45);
      transition: all 0.25s ease;
      min-width: 0;
    }

    .stat-card:hover {
      border-color: var(--card-hover-border);
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.35), 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 20px rgba(245, 158, 11, 0.12);
      transform: translateY(-3px);
    }

    .stat-icon-wrap {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(245, 158, 11, 0.16) 0%, rgba(224, 122, 63, 0.08) 100%);
      border: 1px solid rgba(245, 158, 11, 0.22);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fbbf24;
      margin-bottom: 16px;
    }

    .stat-number {
      font-size: 32px;
      font-weight: 800;
      color: #ffffff;
      line-height: 1;
      margin-bottom: 6px;
    }

    .stat-label {
      font-size: 13.5px;
      color: var(--text-muted);
      font-weight: 500;
    }

    /* SKILLS */
    .skills-section {
      padding: 100px 0;
    }

    .skills-3col-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 24px;
    }

    .skills-group-card {
      background: var(--card-bg);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 22px;
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.16), 0 16px 36px rgba(0, 0, 0, 0.45);
      transition: all 0.25s ease;
      min-width: 0;
    }

    .skills-group-card:hover {
      border-color: var(--card-hover-border);
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.35), 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 20px rgba(245, 158, 11, 0.12);
      transform: translateY(-3px);
    }

    .skill-row {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .skill-top-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .skill-info-left {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
    }

    .skill-icon-img {
      width: 22px;
      height: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .skill-name {
      font-size: 14.5px;
      font-weight: 600;
      color: #ffffff;
    }

    .skill-pct {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-muted);
    }

    .skill-bar-track {
      width: 100%;
      height: 6px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 9999px;
      overflow: hidden;
    }

    .skill-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #e07a3f 100%);
      border-radius: 9999px;
    }

    /* PROJECTS */
    .projects-section {
      padding: 100px 0;
    }

    .projects-3col-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 24px;
      margin-bottom: 34px;
    }

    .project-card {
      background: var(--card-bg);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.16), 0 16px 36px rgba(0, 0, 0, 0.45);
      transition: all 0.3s ease;
      min-width: 0;
    }

    .project-card:hover {
      border-color: var(--card-hover-border);
      transform: translateY(-4px);
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.35), 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 22px rgba(245, 158, 11, 0.12);
    }

    .project-thumb-box {
      width: 100%;
      height: 200px;
      position: relative;
      background: #120b08;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .project-num-tag {
      position: absolute;
      top: 14px;
      left: 14px;
      font-size: 12px;
      font-weight: 700;
      color: var(--text-muted);
      background: rgba(20, 12, 9, 0.75);
      backdrop-filter: blur(8px);
      padding: 3px 10px;
      border-radius: 6px;
      border: 1px solid rgba(224, 122, 63, 0.2);
      z-index: 2;
    }

    .project-content {
      padding: 22px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .project-title {
      font-size: 17.5px;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 8px;
    }

    .project-desc {
      font-size: 13.5px;
      color: var(--text-muted);
      line-height: 1.55;
      margin-bottom: 20px;
      flex-grow: 1;
    }

    .project-link {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 13px;
      font-weight: 600;
      color: var(--primary);
      text-decoration: none;
      align-self: flex-end;
      transition: gap 0.2s ease, color 0.2s ease;
    }

    .project-link:hover {
      color: #f59e0b;
      gap: 8px;
    }

    .pagination-dots {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
    }

    .dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.18);
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .dot.active {
      background: var(--primary);
      width: 22px;
      border-radius: 9999px;
    }

    /* CONTACT / TESTIMONIAL / FOLLOW */
    .contact-section {
      padding: 100px 0 50px;
      border-top: 1px solid rgba(224, 122, 63, 0.12);
    }

    .contact-3col-grid {
      display: grid;
      grid-template-columns: 1.15fr 1.35fr 1fr;
      gap: 40px;
      margin-bottom: 70px;
      min-width: 0;
    }

    .contact-cta h2 {
      font-size: 32px;
      font-weight: 800;
      margin-bottom: 16px;
      line-height: 1.25;
    }

    .contact-cta p {
      font-size: 14.5px;
      line-height: 1.65;
      color: var(--text-muted);
      margin-bottom: 26px;
    }

    /* TESTIMONIAL */
    .testimonial-card {
      background: var(--card-bg);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      padding: 26px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.18), 0 16px 36px rgba(0, 0, 0, 0.45);
      transition: all 0.25s ease;
      min-width: 0;
    }

    .testimonial-card:hover {
      border-color: var(--card-hover-border);
      box-shadow: inset 0 1px 0 0 rgba(245, 158, 11, 0.35), 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 20px rgba(245, 158, 11, 0.12);
    }

    .testimonial-quote-icon {
      color: var(--primary);
      font-size: 34px;
      line-height: 1;
      margin-bottom: 10px;
      font-family: Georgia, serif;
    }

    .testimonial-text {
      font-size: 14px;
      line-height: 1.65;
      color: #ede5df;
      margin-bottom: 22px;
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

    /* FOLLOW ME */
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
      margin-bottom: 24px;
      flex-wrap: wrap;
    }

    .social-btn {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: rgba(28, 18, 14, 0.6);
      border: 1px solid var(--card-border);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      text-decoration: none;
      transition: all 0.2s ease;
      flex-shrink: 0;
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
      max-width: 100%;
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
      font-size: 13px;
      color: var(--text-dim);
      gap: 12px;
      min-width: 0;
    }

    .sub-footer>* {
      min-width: 0;
    }

    /* PREVENT GRID OVERFLOW */
    .hero-grid>*,
    .about-grid>*,
    .contact-3col-grid>*,
    .skills-3col-grid>*,
    .projects-3col-grid>* {
      min-width: 0;
      max-width: 100%;
    }

    /* =====================================
       TABLET RESPONSIVENESS
    ===================================== */
    @media (max-width: 968px) {

      .hero-grid,
      .about-grid,
      .contact-3col-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 36px;
      }

      .skills-3col-grid,
      .projects-3col-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .hero-title {
        font-size: 40px;
      }

      .nav-links {
        display: none;
      }
    }

    /* =====================================
       MOBILE RESPONSIVENESS
    ===================================== */
    @media (max-width: 768px) {

      html,
      body {
        width: 100%;
        max-width: 100%;
        overflow-x: clip;
      }

      .site-wrapper {
        width: 100%;
        overflow-x: clip;
      }

      .container {
        width: 100%;
        padding-left: 20px;
        padding-right: 20px;
      }

      /* HERO */
      .hero-section {
        padding-top: 110px;
        padding-bottom: 65px;
        min-height: auto;
      }

      .hero-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 30px;
      }

      .hero-title {
        font-size: clamp(34px, 9vw, 42px);
        overflow-wrap: anywhere;
      }

      .hero-desc {
        max-width: 100%;
      }

      .hero-cta-group {
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 32px;
      }

      /* ABOUT */
      .about-section,
      .skills-section,
      .projects-section {
        padding: 65px 0;
      }

      .about-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 30px;
      }

      .about-text h2 {
        font-size: 30px;
      }

      .stats-2x2-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
      }

      .stat-card {
        padding: 18px;
        min-width: 0;
      }

      .stat-number {
        font-size: 27px;
      }

      /* SKILLS */
      .skills-3col-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
      }

      /* PROJECTS */
      .projects-3col-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
      }

      /* CONTACT */
      .contact-section {
        padding: 55px 0 18px;
        margin: 0;
      }

      .contact-3col-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 28px;
        margin-bottom: 28px;
      }

      .contact-cta h2 {
        font-size: 29px;
      }

      .testimonial-card {
        padding: 22px;
        min-width: 0;
      }

      .testimonial-text {
        overflow-wrap: anywhere;
      }

      .contact-item {
        max-width: 100%;
        overflow-wrap: anywhere;
        word-break: break-word;
      }

      /* FOOTER */
      .sub-footer {
        padding-top: 18px;
        padding-bottom: 5px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        font-size: 12px;
      }

      /* GLOWS */
      .ambient-glow {
        max-width: 100%;
        filter: blur(65px);
      }

      /* SECTION HEADINGS */
      .section-header-center {
        margin-bottom: 32px;
      }

      .section-title {
        font-size: 30px;
      }
    }

    /* =====================================
       SMALL MOBILE PHONES
    ===================================== */
    @media (max-width: 380px) {

      .container {
        padding-left: 16px;
        padding-right: 16px;
      }

      .hero-title {
        font-size: 34px;
      }

      .hero-cta-group {
        align-items: flex-start;
      }

      .stats-2x2-grid {
        gap: 10px;
      }

      .stat-card {
        padding: 14px;
      }

      .testimonial-card {
        padding: 18px;
      }

      .social-icons-row {
        gap: 9px;
      }

      .contact-item {
        font-size: 12px;
      }
    }
  </style>

</head>

<body>

  <!-- FIXED CANVAS BACKGROUND (Smooth Scroll Animation) -->
  <div id="canvas-container">
    <canvas id="scroll-canvas"></canvas>
  </div>

  <!-- Warm Ambient Overlay: Blends the canvas frames seamlessly with the warm coffee UI -->
  <div class="bg-ambient-overlay"></div>

  <!-- MAIN PORTFOLIO SECTIONS (Rendered on top of scroll animation) -->
  <div class="site-wrapper">

    <!-- Ambient Gold-Orange Glow Points (Low Opacity) -->
    <div class="ambient-glow glow-point-1"></div>
    <div class="ambient-glow glow-point-2"></div>
    <div class="ambient-glow glow-point-3"></div>
    <div class="ambient-glow glow-point-4"></div>
    <div class="ambient-glow glow-point-5"></div>

    <!-- NAVBAR -->
    <header class="navbar">
      <div class="container nav-inner">
        <a href="<?= e(url('/#home')) ?>" class="nav-logo">
          <span class="nav-logo-icon">&lt;/&gt;</span>
          <span>CiscoCraft</span>
        </a>
        <nav>
          <ul class="nav-links">
            <li><a href="<?= e(url('/#home')) ?>" class="nav-link active">Home</a></li>
            <li><a href="<?= e(url('/#about')) ?>" class="nav-link">About</a></li>
            <li><a href="<?= e(url('/#skills')) ?>" class="nav-link">Skills</a></li>
            <li><a href="<?= e(url('/projects')) ?>" class="nav-link">Projects</a></li>
            <li><a href="<?= e(url('/blog')) ?>" class="nav-link">Blog</a></li>
            <li><a href="<?= e(url('/contact')) ?>" class="nav-link">Contact</a></li>
          </ul>
        </nav>
        <a href="<?= e(url('/contact')) ?>" class="btn-hire">
          Hire Me
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M7 17L17 7M17 7H7M17 7V17" />
          </svg>
        </a>
      </div>
    </header>

    <main>
      <!-- HERO SECTION -->
      <section id="home" class="hero-section">
        <div class="container hero-grid">
          <div>
            <div class="pill-badge">I'M A WEB DEVELOPER</div>
            <h1 class="hero-title">
              Hi, I’m <span class="gradient-alex">Chibuikem</span><br>
              I build things for the web.
            </h1>
            <p class="hero-desc">
              I'm a passionate web developer specializing in building exceptional digital experiences with modern technologies.
            </p>

<div class="hero-cta-group">
<a href="<?= e(url('/projects')) ?>" class="btn-primary">
    View My Work
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M7 17L17 7M17 7H7M17 7V17" />
    </svg>
</a>
                                  


  <a href="<?= e(url('/assets/documents/Nweze_Chibuikem_Academic_CV.pdf')) ?>"
     class="btn-outline"
     download="Chibuikem-CV.pdf">
    Download CV
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
    </svg>
  </a>
</div>


            <div>
              <div class="tech-strip-label">TECHNOLOGIES I WORK WITH</div>
              <div class="tech-icons-row">
                <!-- HTML5 -->
                <div class="tech-badge-icon" title="HTML5">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="#e34f26">
                    <path d="M12 2L3 5l2 14 7 3 7-3 2-14-9-3zm6 5.8h-7.8l.2 2.2h7.4l-.8 8.1-4.8 1.4-4.8-1.4-.3-3.7h2.2l.2 1.9 2.7.7 2.7-.7.4-3.7H6.9L6.3 5.8h11.7v2z" />
                  </svg>
                </div>
                <!-- CSS3 -->
                <div class="tech-badge-icon" title="CSS3">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="#1572b6">
                    <path d="M12 2L3 5l2 14 7 3 7-3 2-14-9-3zm6 5.8h-7.8l.2 2.2h7.4l-.8 8.1-4.8 1.4-4.8-1.4-.3-3.7h2.2l.2 1.9 2.7.7 2.7-.7.4-3.7H6.9L6.3 5.8h11.7v2z" />
                  </svg>
                </div>
                <!-- JS -->
                <div class="tech-badge-icon" title="JavaScript">
                  <svg width="22" height="22" viewBox="0 0 24 24">
                    <rect width="24" height="24" rx="4" fill="#f7df1e" />
                    <path d="M7 17.5c.8.6 1.8.9 2.8.9 1.4 0 2.2-.7 2.2-2.1v-7.8H9.8V16c0 .7-.3 1-1 1-.4 0-.8-.1-1.1-.3l-.7.8zm8.2-.1c1 .6 2.1 1 3.2 1 1.8 0 2.9-1 2.9-2.5 0-1.4-.8-2.2-2.3-2.9l-.7-.3c-.9-.4-1.4-.8-1.4-1.4 0-.6.5-1.1 1.4-1.1.8 0 1.5.3 2 .8l1-.9c-.7-.7-1.7-1.1-2.9-1.1-1.7 0-2.8 1-2.8 2.4 0 1.3.8 2.1 2.2 2.7l.8.3c1 .4 1.5.8 1.5 1.5 0 .8-.6 1.3-1.6 1.3-.9 0-1.8-.4-2.4-1l-.9.9z" fill="#000" />
                  </svg>
                </div>
                <!-- PHP -->
                <div class="tech-badge-icon" title="TypeScript">
                  <svg width="20" height="20" viewBox="0 0 256 256" xmlns="http://w3.org">
                    <!-- Brighter, deeper brand-accurate purple oval -->
                    <ellipse cx="128" cy="128" rx="124" ry="82" fill="#4F5B93" />
                    <!-- Maximized scale, bolder font weight, and ultra-crisp presentation -->
                    <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-weight="950" font-size="108" font-style="italic" fill="#FFFFFF" letter-spacing="-2">php</text>
                  </svg>
                </div>
                <!-- React -->
                <div class="tech-badge-icon" title="React">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="#61dafb">
                    <ellipse cx="12" cy="12" rx="4" ry="10" transform="rotate(30 12 12)" fill="none" stroke="#61dafb" stroke-width="1.2" />
                    <ellipse cx="12" cy="12" rx="4" ry="10" transform="rotate(90 12 12)" fill="none" stroke="#61dafb" stroke-width="1.2" />
                    <ellipse cx="12" cy="12" rx="4" ry="10" transform="rotate(150 12 12)" fill="none" stroke="#61dafb" stroke-width="1.2" />
                    <circle cx="12" cy="12" r="2" fill="#61dafb" />
                  </svg>
                </div>
                <!-- Next.js -->
                <div class="tech-badge-icon" title="Next.js">
                  <svg width="22" height="22" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" fill="#000" />
                    <path d="M16.5 17.5L8.5 7.5h1.8l7.2 9v1h-1zM7.5 7.5h1.8v9H7.5z" fill="#fff" />
                  </svg>
                </div>
                <!-- Git -->
                <div class="tech-badge-icon" title="Git">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="#f05032">
                    <path d="M12 2a2 2 0 0 0-1.4.6L2.6 10.6a2 2 0 0 0 0 2.8l8 8a2 2 0 0 0 2.8 0l8-8a2 2 0 0 0 0-2.8l-8-8A2 2 0 0 0 12 2zm1 6.5a1.5 1.5 0 1 1-2 1.4v4.2a1.5 1.5 0 1 1-1 1.4V10a1.5 1.5 0 0 1 1-1.4V8.5a1.5 1.5 0 0 1 2 0z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Hero Right: Floating Code Card (Replaces static background photo with interactive dev aesthetic) -->
          <div>
            <div class="code-preview-card">
              <div class="code-card-header">
                <span class="code-card-title">
                  <span style="color:#e07a3f">&lt;/&gt;</span> Code
                </span>
                <span class="status-dot-amber"></span>
              </div>
              <div class="code-card-body">
                <div><span class="code-kw">const</span> <span class="code-var">developer</span> <span class="code-punct">=</span> <span class="code-punct">{</span></div>
                <div style="padding-left: 20px;">
                  <span class="code-prop">name</span><span class="code-punct">:</span> <span class="code-str">"Chibuikem"</span><span class="code-punct">,</span>
                </div>
                <div style="padding-left: 20px;">
                  <span class="code-prop">skills</span><span class="code-punct">:</span> <span class="code-punct">[</span><span class="code-str">"HTML"</span><span class="code-punct">,</span> <span class="code-str">"CSS"</span><span class="code-punct">,</span>
                </div>
                <div style="padding-left: 36px;">
                  <span class="code-str">"JavaScript"</span><span class="code-punct">,<span class="code-str">"PHP"</span><span class="code-punct">,</span> <span class="code-str">"React"</span><span class="code-punct">],</span>
                </div>
                
                <div style="padding-left: 20px;">
                  <span class="code-prop">passion</span><span class="code-punct">:</span> <span class="code-str">"Building things"</span>
                </div>
                <div style="padding-left: 36px;">
                  <span class="code-str">"for the web"</span>
                </div>
                <div><span class="code-punct">};</span></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ABOUT ME SECTION -->
      <section id="about" class="about-section">
        <div class="container about-grid">
          <div class="about-text">
            <div class="pill-badge">ABOUT ME</div>
            <h2>I'm passionate about creating digital solutions</h2>
            <p>
              With 4+ years of experience in web development, I help businesses and individuals bring their ideas to life through clean, efficient, and user-friendly code.
            </p>
            <a href="#contact" class="btn-outline">
              Learn More About Me
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
              </svg>
            </a>
          </div>

          <div class="stats-2x2-grid">
            <!-- Stat 1 -->
            <div class="stat-card">
              <div class="stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
              </div>
              <div class="stat-number">4+</div>
              <div class="stat-label">Years Experience</div>
            </div>

            <!-- Stat 2 -->
            <div class="stat-card">
              <div class="stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="16 18 22 12 16 6" />
                  <polyline points="8 6 2 12 8 18" />
                </svg>
              </div>
              <div class="stat-number">50+</div>
              <div class="stat-label">Projects Completed</div>
            </div>

            <!-- Stat 3 -->
            <div class="stat-card">
              <div class="stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10" />
                  <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                  <line x1="9" y1="9" x2="9.01" y2="9" />
                  <line x1="15" y1="9" x2="15.01" y2="9" />
                </svg>
              </div>
              <div class="stat-number">30+</div>
              <div class="stat-label">Happy Clients</div>
            </div>

            <!-- Stat 4 -->
            <div class="stat-card">
              <div class="stat-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.45 1-1 1H8c-.55 0-1 .45-1 1v1h10v-1c0-.55-.45-1-1-1h-1c-.55 0-1-.45-1-1v-2.34M7 4h10v6a5 5 0 0 1-10 0V4z" />
                </svg>
              </div>
              <div class="stat-number">100%</div>
              <div class="stat-label">Client Satisfaction</div>
            </div>
          </div>
        </div>
      </section>

      <!-- SKILLS SECTION (Grouped 3-card layout as in the reference image) -->
      <section id="skills" class="skills-section">
        <div class="container">
          <div class="section-header-center">
            <div class="pill-badge">MY SKILLS</div>
            <h2 class="section-title">Technologies I Master</h2>
            <div class="section-title-underline"></div>
          </div>

          <div class="skills-3col-grid">
            <!-- Column 1 Card -->
            <div class="skills-group-card">
              <!-- Skill: HTML -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="#e34f26">
                        <path d="M12 2L3 5l2 14 7 3 7-3 2-14-9-3zm6 5.8h-7.8l.2 2.2h7.4l-.8 8.1-4.8 1.4-4.8-1.4-.3-3.7h2.2l.2 1.9 2.7.7 2.7-.7.4-3.7H6.9L6.3 5.8h11.7v2z" />
                      </svg>
                    </div>
                    <span class="skill-name">HTML</span>
                  </div>
                  <span class="skill-pct">95%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 95%;"></div>
                </div>
              </div>

              <!-- Skill: CSS -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="#1572b6">
                        <path d="M12 2L3 5l2 14 7 3 7-3 2-14-9-3zm6 5.8h-7.8l.2 2.2h7.4l-.8 8.1-4.8 1.4-4.8-1.4-.3-3.7h2.2l.2 1.9 2.7.7 2.7-.7.4-3.7H6.9L6.3 5.8h11.7v2z" />
                      </svg>
                    </div>
                    <span class="skill-name">CSS</span>
                  </div>
                  <span class="skill-pct">90%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 90%;"></div>
                </div>
              </div>

              <!-- Skill: JavaScript -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24">
                        <rect width="24" height="24" rx="4" fill="#f7df1e" />
                        <path d="M7 17.5c.8.6 1.8.9 2.8.9 1.4 0 2.2-.7 2.2-2.1v-7.8H9.8V16c0 .7-.3 1-1 1-.4 0-.8-.1-1.1-.3l-.7.8zm8.2-.1c1 .6 2.1 1 3.2 1 1.8 0 2.9-1 2.9-2.5 0-1.4-.8-2.2-2.3-2.9l-.7-.3c-.9-.4-1.4-.8-1.4-1.4 0-.6.5-1.1 1.4-1.1.8 0 1.5.3 2 .8l1-.9c-.7-.7-1.7-1.1-2.9-1.1-1.7 0-2.8 1-2.8 2.4 0 1.3.8 2.1 2.2 2.7l.8.3c1 .4 1.5.8 1.5 1.5 0 .8-.6 1.3-1.6 1.3-.9 0-1.8-.4-2.4-1l-.9.9z" fill="#000" />
                      </svg>
                    </div>
                    <span class="skill-name">JavaScript</span>
                  </div>
                  <span class="skill-pct">90%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 90%;"></div>
                </div>
              </div>
            </div>

            <!-- Column 2 Card -->
            <div class="skills-group-card">
              <!-- Skill: React.js -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="#61dafb">
                        <ellipse cx="12" cy="12" rx="4" ry="10" transform="rotate(30 12 12)" fill="none" stroke="#61dafb" stroke-width="1.5" />
                        <ellipse cx="12" cy="12" rx="4" ry="10" transform="rotate(90 12 12)" fill="none" stroke="#61dafb" stroke-width="1.5" />
                        <ellipse cx="12" cy="12" rx="4" ry="10" transform="rotate(150 12 12)" fill="none" stroke="#61dafb" stroke-width="1.5" />
                        <circle cx="12" cy="12" r="2" fill="#61dafb" />
                      </svg>
                    </div>
                    <span class="skill-name">React.js</span>
                  </div>
                  <span class="skill-pct">85%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 85%;"></div>
                </div>
              </div>

              <!-- Skill: Next.js -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" fill="#000" />
                        <path d="M16 17L8 7.5h1.5L17 16.5v1h-1zM7.5 7.5h1.8v9H7.5z" fill="#fff" />
                      </svg>
                    </div>
                    <span class="skill-name">Next.js</span>
                  </div>
                  <span class="skill-pct">80%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 80%;"></div>
                </div>
              </div>

              <!-- Skill: PHP -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 256 256" xmlns="http://w3.org">
                        <!-- Brighter, deeper brand-accurate purple oval -->
                        <ellipse cx="128" cy="128" rx="124" ry="82" fill="#4F5B93" />
                        <!-- Maximized scale, bolder font weight, and ultra-crisp presentation -->
                        <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-weight="950" font-size="108" font-style="italic" fill="#FFFFFF" letter-spacing="-2">php</text>
                      </svg>
                    </div>
                    <span class="skill-name">PHP</span>
                  </div>
                  <span class="skill-pct">85%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 85%;"></div>
                </div>
              </div>
            </div>

            <!-- Column 3 Card -->
            <div class="skills-group-card">
              <!-- Skill: Node.js -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="#68a063">
                        <path d="M12 2l9 5.2v10.4L12 23l-9-5.4V7.2L12 2zm0 2.3L5 8.3v7.4l7 4.1 7-4.1V8.3L12 4.3z" />
                      </svg>
                    </div>
                    <span class="skill-name">Node.js</span>
                  </div>
                  <span class="skill-pct">80%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 80%;"></div>
                </div>
              </div>

              <!-- Skill: Tailwind CSS -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="#38bdf8">
                        <path d="M12 6c-2.4 0-3.9 1.2-4.5 3.6 1-.9 2.1-1.3 3.3-1.1 1 .2 1.8 1 2.6 1.9C14.7 11.8 16.2 13 18.6 13c2.4 0 3.9-1.2 4.5-3.6-1 .9-2.1 1.3-3.3 1.1-1-.2-1.8-1-2.6-1.9C15.9 7.2 14.4 6 12 6zm-7.2 7c-2.4 0-3.9 1.2-4.5 3.6 1-.9 2.1-1.3 3.3-1.1 1 .2 1.8 1 2.6 1.9C7.5 18.8 9 20 11.4 20c2.4 0 3.9-1.2 4.5-3.6-1 .9-2.1 1.3-3.3 1.1-1-.2-1.8-1-2.6-1.9C8.7 14.2 7.2 13 4.8 13z" />
                      </svg>
                    </div>
                    <span class="skill-name">Tailwind CSS</span>
                  </div>
                  <span class="skill-pct">90%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 90%;"></div>
                </div>
              </div>

              <!-- Skill: Git -->
              <div class="skill-row">
                <div class="skill-top-row">
                  <div class="skill-info-left">
                    <div class="skill-icon-img">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="#f05032">
                        <path d="M12 2a2 2 0 0 0-1.4.6L2.6 10.6a2 2 0 0 0 0 2.8l8 8a2 2 0 0 0 2.8 0l8-8a2 2 0 0 0 0-2.8l-8-8A2 2 0 0 0 12 2zm1 6.5a1.5 1.5 0 1 1-2 1.4v4.2a1.5 1.5 0 1 1-1 1.4V10a1.5 1.5 0 0 1 1-1.4V8.5a1.5 1.5 0 0 1 2 0z" />
                      </svg>
                    </div>
                    <span class="skill-name">Git</span>
                  </div>
                  <span class="skill-pct">85%</span>
                </div>
                <div class="skill-bar-track">
                  <div class="skill-bar-fill" style="width: 85%;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FEATURED PROJECTS SECTION -->
      <section id="projects" class="projects-section">
        <div class="container">
          <div class="section-header-center">
            <div class="pill-badge">FEATURED PROJECTS</div>
            <h2 class="section-title">Some of My Recent Work</h2>
            <div class="section-title-underline"></div>
          </div>

          <div class="projects-3col-grid">
            <!-- Project 1 -->
            <div class="project-card">
              <div class="project-thumb-box">
                <span class="project-num-tag">01</span>
                <!-- Mockup Graphic for E-Commerce Dashboard -->
                <svg width="100%" height="100%" viewBox="0 0 320 200" preserveAspectRatio="none">
                  <defs>
                    <linearGradient id="warmGrad1" x1="0%" y1="0%" x2="100%" y2="100%">
                      <stop offset="0%" stop-color="#24140e" />
                      <stop offset="100%" stop-color="#0f0907" />
                    </linearGradient>
                  </defs>
                  <rect width="320" height="200" fill="url(#warmGrad1)" />
                  <rect x="25" y="45" width="130" height="110" rx="8" fill="#1c110c" stroke="#3d2319" stroke-width="1" />
                  <rect x="35" y="60" width="70" height="8" rx="3" fill="#e07a3f" />
                  <rect x="35" y="76" width="110" height="4" rx="2" fill="#5c3626" />
                  <rect x="35" y="86" width="90" height="4" rx="2" fill="#5c3626" />
                  <rect x="35" y="110" width="55" height="18" rx="4" fill="#d96826" />

                  <rect x="175" y="45" width="120" height="110" rx="8" fill="#180e0a" stroke="#3d2319" stroke-width="1" />
                  <polygon points="190,120 220,80 250,105 280,65" fill="none" stroke="#e07a3f" stroke-width="3" />
                  <circle cx="280" cy="65" r="4" fill="#f59e0b" />
                  <circle cx="230" cy="120" r="14" fill="none" stroke="#d96826" stroke-width="4" />
                </svg>
              </div>
              <div class="project-content">
                <h3 class="project-title">E-Commerce Platform</h3>
                <p class="project-desc">Full-stack e-commerce solution with modern UI/UX</p>
                <a href="<?= e(url('/projects')) ?>" class="project-link">
                  View Project
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
              </div>
            </div>

            <!-- Project 2 -->
            <div class="project-card">
              <div class="project-thumb-box">
                <span class="project-num-tag">02</span>
                <!-- Mockup Graphic for Task Management App -->
                <svg width="100%" height="100%" viewBox="0 0 320 200" preserveAspectRatio="none">
                  <rect width="320" height="200" fill="#f8fafc" />
                  <rect x="20" y="30" width="280" height="145" rx="8" fill="#ffffff" stroke="#e2e8f0" stroke-width="1" />
                  <rect x="35" y="45" width="60" height="8" rx="3" fill="#e07a3f" />
                  <circle cx="270" cy="49" r="6" fill="#cbd5e1" />
                  <!-- Kanban cards -->
                  <rect x="35" y="65" width="75" height="45" rx="5" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="1" />
                  <rect x="42" y="74" width="45" height="5" rx="2" fill="#475569" />
                  <rect x="42" y="85" width="60" height="4" rx="2" fill="#94a3b8" />
                  <rect x="35" y="118" width="75" height="45" rx="5" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="1" />

                  <rect x="122" y="65" width="75" height="60" rx="5" fill="#fef3c7" stroke="#fde68a" stroke-width="1" />
                  <rect x="130" y="74" width="50" height="5" rx="2" fill="#b45309" />
                  <rect x="130" y="85" width="40" height="12" rx="3" fill="#e07a3f" />

                  <rect x="210" y="65" width="75" height="50" rx="5" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="1" />
                  <rect x="218" y="74" width="45" height="5" rx="2" fill="#10b981" />
                </svg>
              </div>
              <div class="project-content">
                <h3 class="project-title">Real estate Platform</h3>
                <p class="project-desc">Collaborative task management application</p>
                <a href="<?= e(url('/projects')) ?>" class="project-link">
                  View Project
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
              </div>
            </div>

            <!-- Project 3 -->
            <div class="project-card">
              <div class="project-thumb-box">
                <span class="project-num-tag">03</span>
                <!-- Mockup Graphic for Crypto Dashboard -->
                <svg width="100%" height="100%" viewBox="0 0 320 200" preserveAspectRatio="none">
                  <rect width="320" height="200" fill="#0c0705" />
                  <rect x="20" y="30" width="280" height="145" rx="8" fill="#170f0b" stroke="#2b1a12" stroke-width="1" />
                  <rect x="35" y="45" width="70" height="10" rx="3" fill="#e07a3f" />
                  <rect x="35" y="60" width="45" height="6" rx="2" fill="#786b63" />

                  <path d="M40 120 L80 100 L120 125 L160 85 L200 95 L240 65 L275 75" fill="none" stroke="#10b981" stroke-width="2.5" />
                  <circle cx="240" cy="65" r="4" fill="#34d399" />

                  <rect x="50" y="135" width="12" height="25" rx="2" fill="#10b981" opacity="0.6" />
                  <rect x="75" y="142" width="12" height="18" rx="2" fill="#ef4444" opacity="0.6" />
                  <rect x="100" y="130" width="12" height="30" rx="2" fill="#10b981" opacity="0.6" />
                  <rect x="125" y="125" width="12" height="35" rx="2" fill="#10b981" opacity="0.6" />

                  <circle cx="250" cy="138" r="16" fill="none" stroke="#e07a3f" stroke-width="4" />
                  <circle cx="250" cy="138" r="16" fill="none" stroke="#f59e0b" stroke-width="4" stroke-dasharray="25 75" />
                </svg>
              </div>
              <div class="project-content">
                <h3 class="project-title">Crypto Dashboard</h3>
                <p class="project-desc">Real-time cryptocurrency tracking dashboard</p>
                <a href="<?= e(url('/projects')) ?>" class="project-link">
                  View Project
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M7 17L17 7M17 7H7M17 7V17" />
                  </svg>
                </a>
              </div>
            </div>
          </div>

          <!-- Pagination dots -->
          <div class="pagination-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
          </div>

          <div style="text-align: center; margin-top: 34px;">
            <a href="<?= e(url('/projects')) ?>" class="btn-outline">
              Explore All Projects
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M7 17L17 7M17 7H7M17 7V17" />
              </svg>
            </a>
          </div>
        </div>
      </section>

      <!-- CONTACT / TESTIMONIAL / FOOTER SECTION -->
      <section id="contact" class="contact-section">
        <div class="container">
          <div class="contact-3col-grid">
            <!-- Left: Let's Work Together -->
            <div class="contact-cta">
              <div class="pill-badge">LET'S WORK TOGETHER</div>
              <h2>Have a project in mind?</h2>
              <p>
                I'm always open to discussing new projects and opportunities. Let's create something amazing together!
              </p>
              <a href="<?= e(url('/contact')) ?>" class="btn-primary">
                Get in Touch
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M7 17L17 7M17 7H7M17 7V17" />
                </svg>
              </a>
            </div>

            <!-- Middle: Testimonial Card -->
            <div class="testimonial-card">
              <div>
                <div class="testimonial-quote-icon">“</div>
                <p class="testimonial-text">
                  "Chibuikem is an exceptional developer who delivers high-quality work on time. His attention to detail and problem-solving skills are outstanding."
                </p>
              </div>
              <div class="testimonial-author">
                <svg class="author-avatar" viewBox="0 0 40 40" width="40" height="40">
                  <circle cx="20" cy="20" r="20" fill="#2d170e" />
                  <circle cx="20" cy="15" r="7" fill="#f5d0b8" />
                  <path d="M6 35c0-7.7 6.3-14 14-14s14 6.3 14 14" fill="#e07a3f" />
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
                <!-- GitHub -->
                <a href="https://github.com" target="_blank" rel="noreferrer" class="social-btn" title="GitHub">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z" />
                  </svg>
                </a>
                <!-- LinkedIn -->
                <a href="https://linkedin.com" target="_blank" rel="noreferrer" class="social-btn" title="LinkedIn">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.6a1.64 1.64 0 0 0-1.64 1.64c0 .91.73 1.64 1.64 1.64s1.64-.73 1.64-1.64A1.64 1.64 0 0 0 7.83 6.6z" />
                  </svg>
                </a>
                <!-- Twitter/X -->
                <a href="https://twitter.com" target="_blank" rel="noreferrer" class="social-btn" title="Twitter/X">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                  </svg>
                </a>
                <!-- Instagram -->
                <a href="https://instagram.com" target="_blank" rel="noreferrer" class="social-btn" title="Instagram">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                  </svg>
                </a>
              </div>

              <div>
                <a href="mailto:chibuikemnweze2020@gmail.com" class="contact-item">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                    <polyline points="22,6 12,13 2,6" />
                  </svg>
                  <span>chibuikemnweze2020@gmail.com</span>
                </a>
                <a href="tel:+15551234587" class="contact-item">
                  <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                  </svg>
                  <span>+234 9161426400</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Bottom Sub-Footer -->
          <div class="sub-footer">
            <div>&copy; 2024 CiscoCraft Developer. All rights reserved.</div>
            <div>Made with ❤️ by CiscoCraft</div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- SMOOTH SCROLL ANIMATION ENGINE -->
  <script>
    (function() {
      const TOTAL_FRAMES = 238;
      const FOLDER = 'ezgif-8fa0b27346b5e345-jpg';
      const canvas = document.getElementById('scroll-canvas');
      const ctx = canvas.getContext('2d', {
        alpha: false
      });

      const images = new Array(TOTAL_FRAMES);
      let lastDrawnImage = null;
      let lastDrawnWidth = 0;
      let lastDrawnHeight = 0;

      function getFramePath(index) {
        const frameNum = String(index + 1).padStart(3, '0');
        return `${FOLDER}/ezgif-frame-${frameNum}.jpg`;
      }

      // Smooth inertia interpolation
      let targetProgress = 0;
      let currentProgress = 0;
      const LERP_FACTOR = 0.08;

      function handleResize() {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = Math.round(window.innerWidth * dpr);
        canvas.height = Math.round(window.innerHeight * dpr);
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = 'high';
        lastDrawnImage = null; // force redraw
        updateTargetProgress();
      }

      window.addEventListener('resize', handleResize);
      handleResize();

      function drawFrame(frameIndex) {
        let img = images[frameIndex];

        // Graceful fallback to nearest available loaded frame
        if (!img || !img.complete || img.naturalWidth === 0) {
          for (let offset = 1; offset < TOTAL_FRAMES; offset++) {
            const before = frameIndex - offset;
            const after = frameIndex + offset;
            if (before >= 0 && images[before] && images[before].complete && images[before].naturalWidth > 0) {
              img = images[before];
              break;
            }
            if (after < TOTAL_FRAMES && images[after] && images[after].complete && images[after].naturalWidth > 0) {
              img = images[after];
              break;
            }
          }
        }

        if (!img || !img.complete || img.naturalWidth === 0) return;

        // Skip redundant draw
        if (img === lastDrawnImage && canvas.width === lastDrawnWidth && canvas.height === lastDrawnHeight) {
          return;
        }

        const cw = canvas.width;
        const ch = canvas.height;
        const iw = img.naturalWidth;
        const ih = img.naturalHeight;

        // Centered cover calculation
        const scale = Math.max(cw / iw, ch / ih);
        const nw = iw * scale;
        const nh = ih * scale;
        const cx = (cw - nw) * 0.5;
        const cy = (ch - nh) * 0.5;

        ctx.drawImage(img, 0, 0, iw, ih, cx, cy, nw, nh);

        lastDrawnImage = img;
        lastDrawnWidth = cw;
        lastDrawnHeight = ch;
      }

      // Preload frames in memory
      for (let i = 0; i < TOTAL_FRAMES; i++) {
        const img = new Image();
        img.src = getFramePath(i);
        img.onload = () => {
          if (!lastDrawnImage && i === 0) {
            drawFrame(0);
          }
        };
        images[i] = img;
      }

      function updateTargetProgress() {
        const scrollTop = window.scrollY || window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        const scrollHeight = Math.max(
          document.body.scrollHeight,
          document.documentElement.scrollHeight
        );
        const clientHeight = window.innerHeight || document.documentElement.clientHeight || 1;
        const maxScroll = scrollHeight - clientHeight;

        targetProgress = maxScroll > 0 ? Math.min(1, Math.max(0, scrollTop / maxScroll)) : 0;
      }

      window.addEventListener('scroll', updateTargetProgress, {
        passive: true
      });
      updateTargetProgress();

      // Active nav link highlight on scroll
      const sections = document.querySelectorAll('section[id]');
      const navLinks = document.querySelectorAll('.nav-link');

      window.addEventListener('scroll', () => {
        let currentSectionId = '';
        const scrollPos = window.scrollY + 200;

        sections.forEach(sec => {
          const top = sec.offsetTop;
          const height = sec.offsetHeight;
          if (scrollPos >= top && scrollPos < top + height) {
            currentSectionId = sec.getAttribute('id');
          }
        });

        navLinks.forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === `#${currentSectionId}`) {
            link.classList.add('active');
          }
        });
      }, {
        passive: true
      });

      // Continuous RAF loop with silky inertia
      function tick() {
        const diff = targetProgress - currentProgress;
        if (Math.abs(diff) > 0.0001) {
          currentProgress += diff * LERP_FACTOR;
        } else {
          currentProgress = targetProgress;
        }

        const targetFrameIndex = Math.min(
          TOTAL_FRAMES - 1,
          Math.max(0, Math.round(currentProgress * (TOTAL_FRAMES - 1)))
        );

        drawFrame(targetFrameIndex);
        requestAnimationFrame(tick);
      }

      requestAnimationFrame(tick);
    })();
  </script>
  <?php require __DIR__ . '/component/whatsapp.php'; ?>
</body>

</html>