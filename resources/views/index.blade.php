@extends('layouts.user')

@section('custom_style')
    <link href="{{ asset('assets/css/tailwind-built.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --bg-deep: #060B18;
            --cyan-glow: rgba(34, 211, 238, 0.6);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #060B18 !important;
            color: #cbd5e1;
            /* slate-300 */
            overflow-x: hidden;
        }

        .animated-circuit-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, #0a1628 0%, #060b18 100%);
            overflow: hidden;
        }

        .circuit-path {
            stroke: #22d3ee;
            stroke-width: 1.5;
            fill: none;
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            opacity: 0.15;
            animation: dash 10s linear infinite;
        }

        @keyframes dash {
            to {
                stroke-dashoffset: 0;
            }
        }

        .circuit-dot {
            fill: #22d3ee;
            filter: drop-shadow(0 0 3px #22d3ee);
            animation: pulse-dot 4s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 0.2;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }


        .interactive-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .interactive-card:hover {
            transform: scale(1.05) translateY(-5px);
            z-index: 10;
            border-color: #22d3ee;
            box-shadow: 0 0 25px var(--cyan-glow);
        }

        .hover-overlay {
            opacity: 0;
            transition: all 0.4s ease;
            background: linear-gradient(to top, rgba(6, 11, 24, 0.95) 0%, rgba(6, 11, 24, 0.4) 50%, transparent 100%);
        }

        .interactive-card:hover .hover-overlay {
            opacity: 1;
        }

        .top-up-badge {
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .interactive-card:hover .top-up-badge {
            transform: translateY(0);
            opacity: 1;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .tab-active {
            border-bottom: 3px solid #0EA5E9;
            color: #0EA5E9;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .circuit-pattern {
            background-image:
                radial-gradient(circle at 50% 50%, rgba(14, 165, 233, 0.1) 0%, transparent 50%),
                linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.95)),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 1.79 4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 1.79 4 4 1.79 4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%230ea5e9' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .glass {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .glass-card:hover {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(14, 165, 233, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 10px 40px -10px rgba(14, 165, 233, 0.2);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.25) 0%, rgba(6, 11, 24, 0.6) 100%);
            border: 1px solid rgba(14, 165, 233, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }


        .shadow-blue-glow {
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.4);
        }

        .shadow-yellow-glow {
            box-shadow: 0 0 20px rgba(245, 199, 84, 0.3);
        }

        .font-ml {
            font-family: 'Cinzel Decorative', cursive;
        }

        .text-gradient-ml {
            background: linear-gradient(to bottom, #FFFFFF 0%, #FFFFFF 40%, #ff1f8e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 12px rgba(255, 31, 142, 0.6));
        }

        /* Interactive Cyber Card (Desktop) */
        .uiverse-container {
            position: relative;
            width: 100%;
            transition: 200ms;
            overflow: hidden;
            border-radius: 20px;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .uiverse-container:hover .uiverse-card::before {
            transition: 200ms;
            content: "";
            opacity: 80%;
        }

        .uiverse-container:active {
            transform: scale(0.95);
        }

        .uiverse-card {
            position: absolute;
            inset: 0;
            z-index: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            transition: 700ms;
            background: linear-gradient(45deg, #0f172a, #1e293b);
            border: 2px solid rgba(255, 255, 255, 0.05);
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .uiverse-card-content {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .uiverse-card-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            transition: 700ms;
        }

        .uiverse-prompt {
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            transition: 300ms ease-in-out;
            position: absolute;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            width: 100%;
            text-transform: uppercase;
        }

        .uiverse-title {
            opacity: 0;
            transition: 300ms ease-in-out;
            position: absolute;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 2px;
            text-align: center;
            width: 100%;
            top: 40%;
            transform: translateY(-50%);
            color: #ffffff;
            text-shadow: 0 0 15px rgba(0, 0, 0, 0.8);
            text-transform: uppercase;
            padding: 0 10px;
        }

        .uiverse-subtitle {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 9px;
            letter-spacing: 1px;
            transform: translateY(30px);
            color: #ffffff;
            opacity: 0;
            transition: 300ms;
            font-weight: 800;
            text-transform: uppercase;
        }

        .uiverse-glowing-elements {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .uiverse-glow-1,
        .uiverse-glow-2,
        .uiverse-glow-3 {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle at center, rgba(245, 199, 84, 0.2) 0%, rgba(245, 199, 84, 0) 70%);
            filter: blur(15px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .uiverse-glow-1 {
            top: -20px;
            left: -20px;
        }

        .uiverse-glow-2 {
            top: 50%;
            right: -30px;
            transform: translateY(-50%);
        }

        .uiverse-glow-3 {
            bottom: -20px;
            left: 30%;
        }

        .uiverse-card-particles span {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #f5c754;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Hover effects */
        .uiverse-tracker:hover~.uiverse-card .uiverse-title {
            opacity: 1;
            transform: translateY(-20px);
        }

        .uiverse-tracker:hover~.uiverse-card .uiverse-subtitle {
            opacity: 1;
            transform: translateY(0);
        }

        .uiverse-tracker:hover~.uiverse-card .uiverse-glowing-elements div {
            opacity: 1;
        }

        .uiverse-tracker:hover~.uiverse-card .uiverse-card-particles span {
            animation: uiverseParticleFloat 2s infinite;
        }

        @keyframes uiverseParticleFloat {
            0% {
                transform: translate(0, 0);
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translate(calc(var(--x, 0) * 30px), calc(var(--y, 0) * 30px));
                opacity: 0;
            }
        }

        .uiverse-card-particles span:nth-child(1) {
            --x: 1;
            --y: -1;
            top: 40%;
            left: 20%;
        }

        .uiverse-card-particles span:nth-child(2) {
            --x: -1;
            --y: -1;
            top: 60%;
            right: 20%;
        }

        .uiverse-card-particles span:nth-child(3) {
            --x: 0.5;
            --y: 1;
            top: 20%;
            left: 40%;
        }

        .uiverse-card-particles span:nth-child(4) {
            --x: -0.5;
            --y: 1;
            top: 80%;
            right: 40%;
        }

        .uiverse-card-particles span:nth-child(5) {
            --x: 1;
            --y: 0.5;
            top: 30%;
            left: 60%;
        }

        .uiverse-card-particles span:nth-child(6) {
            --x: -1;
            --y: 0.5;
            top: 70%;
            right: 60%;
        }

        .uiverse-card::before {
            content: "";
            background: radial-gradient(circle at center, rgba(245, 199, 84, 0.08) 0%, rgba(245, 199, 84, 0.03) 50%, transparent 100%);
            filter: blur(20px);
            opacity: 0;
            width: 150%;
            height: 150%;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            transition: opacity 0.3s ease;
        }

        .uiverse-tracker:hover~.uiverse-card::before {
            opacity: 1;
        }

        .uiverse-tracker {
            position: absolute;
            z-index: 200;
            width: 100%;
            height: 100%;
        }

        .uiverse-tracker:hover {
            cursor: pointer;
        }

        .uiverse-tracker:hover~.uiverse-card .uiverse-prompt {
            opacity: 0;
        }

        .uiverse-tracker:hover~.uiverse-card {
            transition: 300ms;
            filter: brightness(1.2);
        }

        .uiverse-tracker:hover~.uiverse-card .uiverse-card-bg {
            opacity: 0.8;
            transform: scale(1.1);
        }

        .uiverse-canvas {
            perspective: 800px;
            inset: 0;
            z-index: 200;
            position: absolute;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
            grid-template-rows: 1fr 1fr 1fr 1fr 1fr;
            gap: 0px 0px;
            grid-template-areas:
                "tr-1 tr-2 tr-3 tr-4 tr-5"
                "tr-6 tr-7 tr-8 tr-9 tr-10"
                "tr-11 tr-12 tr-13 tr-14 tr-15"
                "tr-16 tr-17 tr-18 tr-19 tr-20"
                "tr-21 tr-22 tr-23 tr-24 tr-25";
        }

        .uiverse-tr-1 {
            grid-area: tr-1;
        }

        .uiverse-tr-2 {
            grid-area: tr-2;
        }

        .uiverse-tr-3 {
            grid-area: tr-3;
        }

        .uiverse-tr-4 {
            grid-area: tr-4;
        }

        .uiverse-tr-5 {
            grid-area: tr-5;
        }

        .uiverse-tr-6 {
            grid-area: tr-6;
        }

        .uiverse-tr-7 {
            grid-area: tr-7;
        }

        .uiverse-tr-8 {
            grid-area: tr-8;
        }

        .uiverse-tr-9 {
            grid-area: tr-9;
        }

        .uiverse-tr-10 {
            grid-area: tr-10;
        }

        .uiverse-tr-11 {
            grid-area: tr-11;
        }

        .uiverse-tr-12 {
            grid-area: tr-12;
        }

        .uiverse-tr-13 {
            grid-area: tr-13;
        }

        .uiverse-tr-14 {
            grid-area: tr-14;
        }

        .uiverse-tr-15 {
            grid-area: tr-15;
        }

        .uiverse-tr-16 {
            grid-area: tr-16;
        }

        .uiverse-tr-17 {
            grid-area: tr-17;
        }

        .uiverse-tr-18 {
            grid-area: tr-18;
        }

        .uiverse-tr-19 {
            grid-area: tr-19;
        }

        .uiverse-tr-20 {
            grid-area: tr-20;
        }

        .uiverse-tr-21 {
            grid-area: tr-21;
        }

        .uiverse-tr-22 {
            grid-area: tr-22;
        }

        .uiverse-tr-23 {
            grid-area: tr-23;
        }

        .uiverse-tr-24 {
            grid-area: tr-24;
        }

        .uiverse-tr-25 {
            grid-area: tr-25;
        }

        .uiverse-tr-1:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(20deg) rotateY(-10deg) rotateZ(0deg);
        }

        .uiverse-tr-2:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(20deg) rotateY(-5deg) rotateZ(0deg);
        }

        .uiverse-tr-3:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(20deg) rotateY(0deg) rotateZ(0deg);
        }

        .uiverse-tr-4:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(20deg) rotateY(5deg) rotateZ(0deg);
        }

        .uiverse-tr-5:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(20deg) rotateY(10deg) rotateZ(0deg);
        }

        .uiverse-tr-6:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(10deg) rotateY(-10deg) rotateZ(0deg);
        }

        .uiverse-tr-7:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(10deg) rotateY(-5deg) rotateZ(0deg);
        }

        .uiverse-tr-8:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(10deg) rotateY(0deg) rotateZ(0deg);
        }

        .uiverse-tr-9:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(10deg) rotateY(5deg) rotateZ(0deg);
        }

        .uiverse-tr-10:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(10deg) rotateY(10deg) rotateZ(0deg);
        }

        .uiverse-tr-11:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(0deg) rotateY(-10deg) rotateZ(0deg);
        }

        .uiverse-tr-12:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(0deg) rotateY(-5deg) rotateZ(0deg);
        }

        .uiverse-tr-13:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg);
        }

        .uiverse-tr-14:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(0deg) rotateY(5deg) rotateZ(0deg);
        }

        .uiverse-tr-15:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(0deg) rotateY(10deg) rotateZ(0deg);
        }

        .uiverse-tr-16:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-10deg) rotateY(-10deg) rotateZ(0deg);
        }

        .uiverse-tr-17:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-10deg) rotateY(-5deg) rotateZ(0deg);
        }

        .uiverse-tr-18:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-10deg) rotateY(0deg) rotateZ(0deg);
        }

        .uiverse-tr-19:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-10deg) rotateY(5deg) rotateZ(0deg);
        }

        .uiverse-tr-20:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-10deg) rotateY(10deg) rotateZ(0deg);
        }

        .uiverse-tr-21:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-20deg) rotateY(-10deg) rotateZ(0deg);
        }

        .uiverse-tr-22:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-20deg) rotateY(-5deg) rotateZ(0deg);
        }

        .uiverse-tr-23:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-20deg) rotateY(0deg) rotateZ(0deg);
        }

        .uiverse-tr-24:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-20deg) rotateY(5deg) rotateZ(0deg);
        }

        .uiverse-tr-25:hover~.uiverse-card {
            transition: 125ms ease-in-out;
            transform: rotateX(-20deg) rotateY(10deg) rotateZ(0deg);
        }

        .uiverse-card-glare {
            position: absolute;
            inset: 0;
            background: linear-gradient(125deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.05) 45%, rgba(255, 255, 255, 0.1) 50%, rgba(255, 255, 255, 0.05) 55%, rgba(255, 255, 255, 0) 100%);
            opacity: 0;
            transition: opacity 300ms;
        }

        .uiverse-cyber-lines span {
            position: absolute;
            background: linear-gradient(90deg, transparent, rgba(245, 199, 84, 0.2), transparent);
        }

        .uiverse-cyber-lines span:nth-child(1) {
            top: 20%;
            left: 0;
            width: 100%;
            height: 1px;
            transform: scaleX(0);
            transform-origin: left;
            animation: uiverseLineGrow 3s linear infinite;
        }

        .uiverse-cyber-lines span:nth-child(2) {
            top: 40%;
            right: 0;
            width: 100%;
            height: 1px;
            transform: scaleX(0);
            transform-origin: right;
            animation: uiverseLineGrow 3s linear infinite 1s;
        }

        .uiverse-cyber-lines span:nth-child(3) {
            top: 60%;
            left: 0;
            width: 100%;
            height: 1px;
            transform: scaleX(0);
            transform-origin: left;
            animation: uiverseLineGrow 3s linear infinite 2s;
        }

        .uiverse-cyber-lines span:nth-child(4) {
            top: 80%;
            right: 0;
            width: 100%;
            height: 1px;
            transform: scaleX(0);
            transform-origin: right;
            animation: uiverseLineGrow 3s linear infinite 1.5s;
        }

        .uiverse-corner-elements span {
            position: absolute;
            width: 15px;
            height: 15px;
            border: 1px solid rgba(245, 199, 84, 0.2);
            transition: all 0.3s ease;
        }

        .uiverse-corner-elements span:nth-child(1) {
            top: 10px;
            left: 10px;
            border-right: 0;
            border-bottom: 0;
        }

        .uiverse-corner-elements span:nth-child(2) {
            top: 10px;
            right: 10px;
            border-left: 0;
            border-bottom: 0;
        }

        .uiverse-corner-elements span:nth-child(3) {
            bottom: 10px;
            left: 10px;
            border-right: 0;
            border-top: 0;
        }

        .uiverse-corner-elements span:nth-child(4) {
            bottom: 10px;
            right: 10px;
            border-left: 0;
            border-top: 0;
        }

        .uiverse-scan-line {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(245, 199, 84, 0.05), transparent);
            transform: translateY(-100%);
            animation: uiverseScanMove 2s linear infinite;
        }

        @keyframes uiverseLineGrow {
            0% {
                transform: scaleX(0);
                opacity: 0;
            }

            50% {
                transform: scaleX(1);
                opacity: 1;
            }

            100% {
                transform: scaleX(0);
                opacity: 0;
            }
        }

        @keyframes uiverseScanMove {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(100%);
            }
        }

        .uiverse-card:hover .uiverse-card-glare {
            opacity: 1;
        }

        .uiverse-card:hover .uiverse-corner-elements span {
            border-color: rgba(245, 199, 84, 0.6);
            box-shadow: 0 0 10px rgba(245, 199, 84, 0.3);
        }

        /* Redesigned Tab and Card Styles */
        .tab-active {
            background-color: #f5c754 !important;
            color: #0f172a !important;
            box-shadow: 0 0 20px rgba(245, 199, 84, 0.3);
        }

        .interactive-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .interactive-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
        }

        .populer-pill:hover {
            transform: translateX(5px);
        }

        /* Popular Grid Logic */
        #populer-scroll-desktop {
            display: none;
        }

        @media (min-width: 768px) {
            #populer-scroll-desktop {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        @media (min-width: 1024px) {
            #populer-scroll-desktop {
                grid-template-columns: repeat(7, 1fr);
            }
        }

        @media (min-width: 1280px) {
            #populer-scroll-desktop {
                grid-template-columns: repeat(8, 1fr);
            }
        }

        .uiverse-spacer {
            padding-bottom: 133.33%;
            /* 3:4 Aspect Ratio */
        }
    </style>
@endsection

@section('content')
    <div class="animated-circuit-bg">
        <svg height="100%" preserveAspectRatio="xMidYMid slice" viewBox="0 0 1440 900" width="100%"
            xmlns="http://www.w3.org/2000/svg">
            <g class="circuit-lines">
                <path class="circuit-path" d="M100 100 L300 100 L350 150 L600 150 L650 100 L900 100"></path>
                <circle class="circuit-dot" cx="100" cy="100" r="3"></circle>
                <circle class="circuit-dot" cx="900" cy="100" r="3"></circle>
                <path class="circuit-path" d="M1200 800 L1000 800 L950 750 L700 750 L650 800 L400 800"
                    style="animation-delay: -2s;"></path>
                <circle class="circuit-dot" cx="1200" cy="800" r="3"></circle>
                <circle class="circuit-dot" cx="400" cy="800" r="3"></circle>
                <path class="circuit-path" d="M50 400 L200 400 L250 350 L250 200" style="animation-delay: -5s;"></path>
                <circle class="circuit-dot" cx="50" cy="400" r="3"></circle>
                <path class="circuit-path" d="M1400 300 L1250 300 L1200 350 L1200 500 L1250 550 L1400 550"
                    style="animation-delay: -7s;"></path>
                <circle class="circuit-dot" cx="1400" cy="300" r="3"></circle>
                <defs>
                    <pattern height="60" id="grid" patternUnits="userSpaceOnUse" width="60">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(34, 211, 238, 0.05)" stroke-width="0.5">
                        </path>
                    </pattern>
                </defs>
                <rect fill="url(#grid)" height="100%" width="100%"></rect>
            </g>
        </svg>
    </div>

    @include('components.user.navbar')

    <main class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-10 space-y-10 md:space-y-14">
        <!-- Hero Container (Banner & Video Loop) -->
        <div id="hero-loop-container" class="relative group mt-4">
            <!-- Video Player Wrapper -->
            <div id="video-wrapper"
                class="w-full relative aspect-[21/9] lg:aspect-[31/10] xl:aspect-[3/1] min-h-[200px] md:min-h-[400px] rounded-[2rem] md:rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/5 bg-slate-950">
                <div id="youtube-player"
                    class="absolute inset-0 w-full h-full scale-[1.5] md:scale-[1.25] pointer-events-none"></div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-background-dark/80 via-background-dark/20 to-transparent pointer-events-none">
                </div>

                <!-- Video Overlay Text (Branding) -->
                <div class="absolute inset-0 flex items-center px-8 md:px-16 lg:px-24 pointer-events-none z-10">
                    <div class="max-w-xl flex flex-col items-center text-center space-y-2 md:space-y-4">
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('assets/logo/mobile-legends-seeklogo.png') }}" alt="ML Logo"
                                class="h-8 md:h-10 w-auto drop-shadow-lg mb-2">
                            <h2
                                class="text-2xl md:text-5xl lg:text-7xl font-black leading-tight uppercase tracking-tighter text-gradient-ml font-ml">
                                MUSTOPUP
                            </h2>
                            <p
                                class="text-white text-base md:text-2xl lg:text-3xl font-bold uppercase tracking-[0.2em] md:tracking-[0.4em] opacity-90 drop-shadow-lg font-ml">
                                STORE TOPUP GAME
                            </p>
                        </div>

                        <!-- Social Info Bar -->
                        <div
                            class="inline-flex flex-wrap items-center justify-center gap-2 md:gap-8 px-4 py-1.5 md:px-6 md:py-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-full mt-4 opacity-90 shadow-xl">
                            <div
                                class="flex items-center gap-2 text-[10px] md:text-xs font-bold text-white uppercase tracking-wider">
                                <i class="fab fa-facebook text-base md:text-xl" style="color: #1877F2;"></i>
                                MUSTXTRAY STORE
                            </div>
                            <div
                                class="flex items-center gap-2 text-[10px] md:text-xs font-bold text-white uppercase tracking-wider">
                                <i class="fab fa-whatsapp text-base md:text-xl" style="color: #25D366;"></i>
                                +62 812 3456 7890
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fallback Play Button (Only shows if autoplay is blocked) -->
                <div id="video-overlay"
                    class="absolute inset-0 items-center justify-center bg-black/20 cursor-pointer group/overlay hidden"
                    onclick="startVideoManually()">
                    <div
                        class="w-20 h-20 rounded-full bg-secondary/80 flex items-center justify-center text-white shadow-2xl group-hover/overlay:scale-110 transition-all">
                        <span class="material-symbols-outlined text-4xl">play_arrow</span>
                    </div>
                </div>
            </div>

            <!-- Banner Section (Initially Hidden) -->
            <div id="banner-wrapper" class="hidden opacity-0 transition-all duration-1000">
                <div class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar rounded-[2.5rem] shadow-2xl border border-white/5 bg-slate-900"
                    id="carousel">
                    @foreach ($banner as $key => $data)
                        <div class="snap-start min-w-full carousel-item relative aspect-[21/9] lg:aspect-[31/10] xl:aspect-[3/1] overflow-hidden group/slide"
                            id="slide-{{ $key + 1 }}">
                            <a href="{{ $data->url }}">
                                @php
                                    $extension = pathinfo($data->path, PATHINFO_EXTENSION);
                                    $is_video = in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mov']);
                                @endphp
                                @if($is_video)
                                    <video src="{{ asset($data->path) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover/slide:scale-105" autoplay muted loop playsinline></video>
                                @else
                                    <img alt="Banner {{ $key + 1 }}"
                                        class="w-full h-full object-cover transition-transform duration-1000 group-hover/slide:scale-105"
                                        src="{{ asset($data->path) }}" />
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-background-dark/80 via-transparent to-transparent">
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
                    @foreach ($banner as $key => $data)
                        <div class="w-8 h-1 rounded-full {{ $key == 0 ? 'bg-secondary' : 'bg-white/20' }}"></div>
                    @endforeach
                </div>
                <button onclick="document.getElementById('carousel').scrollBy({left: -800, behavior: 'smooth'})"
                    class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-secondary/50">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button onclick="document.getElementById('carousel').scrollBy({left: 800, behavior: 'smooth'})"
                    class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all hover:bg-secondary/50">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>

        <!-- Popular Section -->
        <section>
            <div class="flex justify-between items-center mb-3 md:mb-5">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold flex items-center gap-2">
                        <span
                            class="material-symbols-outlined text-red-500 animate-pulse text-2xl">local_fire_department</span>
                        Populer Sekarang!
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 hidden sm:block">Berikut adalah beberapa produk yang paling
                        populer saat ini</p>
                </div>
                <div class="hidden md:flex gap-2">
                    <button id="populer-prev"
                        onclick="document.getElementById('populer-scroll-desktop').scrollBy({left: -400, behavior: 'smooth'})"
                        class="w-9 h-9 rounded-full bg-slate-800/60 border border-white/10 flex items-center justify-center hover:bg-secondary hover:text-white hover:border-secondary transition-all shadow-lg"><span
                            class="material-symbols-outlined text-lg">chevron_left</span></button>
                    <button id="populer-next"
                        onclick="document.getElementById('populer-scroll-desktop').scrollBy({left: 400, behavior: 'smooth'})"
                        class="w-9 h-9 rounded-full bg-slate-800/60 border border-white/10 flex items-center justify-center hover:bg-secondary hover:text-white hover:border-secondary transition-all shadow-lg"><span
                            class="material-symbols-outlined text-lg">chevron_right</span></button>
                </div>
            </div>

            {{-- Mobile Version: Pill Style --}}
            <div id="populer-scroll-mobile" class="grid grid-cols-2 gap-2 md:hidden md:pb-6 relative w-full h-full">
                @foreach ($kategoris as $category)
                    @if ($category->tipe == 'populer')
                        <a href="{{ url('/id/' . $category->kode) }}"
                            class="populer-pill group flex items-center gap-2 bg-slate-800/50 hover:bg-slate-700/60 border border-white/[0.07] hover:border-secondary/40 rounded-2xl p-2 transition-all duration-300">
                            <div
                                class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 ring-2 ring-white/10 group-hover:ring-secondary/40 transition-all shadow-lg">
                                <img alt="{{ $category->nama }}"
                                    src="{{ str_contains($category->thumbnail, 'http') ? $category->thumbnail : (file_exists(public_path($category->thumbnail)) ? asset($category->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                                    onerror="this.src='{{ asset('assets/thumbnail/hok.webp') }}'; this.onerror=null;"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="text-white font-bold text-xs leading-tight line-clamp-1 group-hover:text-secondary transition-colors">
                                    {{ $category->nama }}</h4>
                                <p class="text-slate-400 text-[10px] mt-0.5 line-clamp-1">{{ $category->sub_nama }}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Desktop Version: Interactive Cyber Cards --}}
            <div id="populer-scroll-desktop" class="relative z-10">
                @foreach ($kategoris as $category)
                    @if ($category->tipe == 'populer')
                        <div class="uiverse-container" onclick="window.location.href='{{ url('/id/' . $category->kode) }}'" style="cursor: pointer;">
                            <div class="uiverse-spacer"></div>
                            <div class="uiverse-canvas absolute inset-0">
                                @for ($i = 1; $i <= 25; $i++)
                                    <div class="uiverse-tracker uiverse-tr-{{ $i }}"></div>
                                @endfor
                                <div class="uiverse-card">
                                    <div class="uiverse-card-content p-2 w-full h-full relative">
                                        <div class="uiverse-card-glare"></div>
                                        <img alt="{{ $category->nama }}"
                                            src="{{ str_contains($category->thumbnail, 'http') ? $category->thumbnail : (file_exists(public_path($category->thumbnail)) ? asset($category->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                                            class="uiverse-card-bg absolute inset-0 object-cover w-full h-full" />

                                        <div class="uiverse-cyber-lines">
                                            <span></span><span></span><span></span><span></span>
                                        </div>
                                        <p
                                            class="uiverse-prompt text-white pointer-events-none drop-shadow-md pb-[4.5rem]">
                                            TOP UP SEKARANG</p>
                                        <div
                                            class="uiverse-title pt-3 mt-1 pb-2 drop-shadow-[0_0_5px_rgba(0,0,0,0.5)] pointer-events-none">
                                            {{ $category->nama }}</div>
                                        <div class="uiverse-glowing-elements pointer-events-none">
                                            <div class="uiverse-glow-1"></div>
                                            <div class="uiverse-glow-2"></div>
                                            <div class="uiverse-glow-3"></div>
                                        </div>
                                        <div
                                            class="uiverse-subtitle font-bold text-shadow pt-5 z-20 sticky pb-1 drop-shadow opacity-0">
                                            <span
                                                class="z-20 leading-5 pt-3 block bottom-0 text-[11px] truncate px-3 shadow-slate-900 border-none">{{ $category->sub_nama }}</span>
                                        </div>
                                        <div class="uiverse-card-particles brightness-200">
                                            <span></span><span></span><span></span> <span></span><span></span><span></span>
                                        </div>
                                        <div class="uiverse-corner-elements">
                                            <span></span><span></span><span></span><span></span>
                                        </div>
                                        <div class="uiverse-scan-line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        <!-- Tab Section -->
        <section
            class="bg-slate-900/40 backdrop-blur-md p-4 sm:p-10 rounded-[2.5rem] md:rounded-[3rem] border border-white/10 shadow-2xl relative">
            {{-- Mobile Tab Navigation --}}
            <div class="relative flex items-center mb-8 md:hidden">
                <button onclick="document.getElementById('tab-scroll').scrollBy({left: -200, behavior: 'smooth'})"
                    class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-800/50 flex items-center justify-center text-white hover:bg-secondary transition-all mr-2">
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </button>
                <div id="tab-scroll" class="flex flex-1 gap-2 overflow-x-auto no-scrollbar scroll-smooth">
                    <button
                        class="tab-btn px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all duration-300 tab-active"
                        data-target-group="topup-panel">Top Up Games</button>
                    <button
                        class="tab-btn px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all duration-300 bg-slate-800/50 text-slate-400 hover:text-white"
                        data-target-group="mlbb-panel">Joki MLBB</button>
                    <button
                        class="tab-btn px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all duration-300 bg-slate-800/50 text-slate-400 hover:text-white"
                        data-target-group="joki-panel">Joki</button>
                    <button
                        class="tab-btn px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all duration-300 bg-slate-800/50 text-slate-400 hover:text-white"
                        data-target-group="voucher-panel">Voucher</button>
                </div>
                <button onclick="document.getElementById('tab-scroll').scrollBy({left: 200, behavior: 'smooth'})"
                    class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-800/50 flex items-center justify-center text-white hover:bg-secondary transition-all ml-2">
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </button>
            </div>

            {{-- Desktop Tab Navigation (Classic) --}}
            <div class="hidden md:flex flex-wrap gap-8 border-b border-white/5 mb-8">
                <button
                    class="tab-btn pb-4 text-sm font-bold tab-active whitespace-nowrap uppercase tracking-wider relative group"
                    data-target-group="topup-panel">
                    Top Up Games
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-secondary scale-x-100 transition-transform origin-left"></span>
                </button>
                <button
                    class="tab-btn pb-4 text-sm font-bold text-slate-500 hover:text-secondary transition-colors whitespace-nowrap uppercase tracking-wider relative group"
                    data-target-group="mlbb-panel">
                    Specialist MLBB
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-secondary scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                </button>
                <button
                    class="tab-btn pb-4 text-sm font-bold text-slate-500 hover:text-secondary transition-colors whitespace-nowrap uppercase tracking-wider relative group"
                    data-target-group="joki-panel">
                    Joki
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-secondary scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                </button>
                <button
                    class="tab-btn pb-4 text-sm font-bold text-slate-500 hover:text-secondary transition-colors whitespace-nowrap uppercase tracking-wider relative group"
                    data-target-group="voucher-panel">
                    Voucher
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-secondary scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                </button>
            </div>

            <div id="topup-panel"
                class="tab-content grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3 sm:gap-6"
                data-items-limit="12">
                @foreach ($kategoris as $key => $category)
                    @if ($category->tipe == 'game' || $category->tipe == 'populer')
                        {{-- Mobile Version --}}
                        <a href="{{ url('/id/' . $category->kode) }}"
                            class="md:hidden group relative aspect-[3/4] bg-slate-900 rounded-3xl overflow-hidden interactive-card border border-white/5 hover:border-secondary/50 transition-all duration-500 shadow-xl {{ $key >= 12 ? 'hidden extra-item' : '' }}">
                            <img alt="{{ $category->nama }}"
                                src="{{ str_contains($category->thumbnail, 'http') ? $category->thumbnail : (file_exists(public_path($category->thumbnail)) ? asset($category->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                                onerror="this.src='{{ asset('assets/thumbnail/hok.webp') }}'; this.onerror=null;"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent flex flex-col justify-end p-1.5 sm:p-4">
                                <h4
                                    class="font-black text-[10px] sm:text-xs text-white uppercase text-center leading-tight drop-shadow-lg group-hover:text-secondary transition-colors">
                                    {{ $category->nama }}</h4>
                            </div>
                        </a>

                        {{-- Desktop Version (Classic) --}}
                        <a href="{{ url('/id/' . $category->kode) }}"
                            class="hidden md:flex flex-col group bg-slate-800/30 rounded-3xl overflow-hidden interactive-card border border-white/5 hover:border-secondary/40 transition-all {{ $key >= 12 ? 'hidden extra-item' : '' }}">
                            <div class="aspect-square overflow-hidden relative">
                                <img alt="{{ $category->nama }}"
                                    src="{{ str_contains($category->thumbnail, 'http') ? $category->thumbnail : (file_exists(public_path($category->thumbnail)) ? asset($category->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                                    onerror="this.src='{{ asset('assets/thumbnail/hok.webp') }}'; this.onerror=null;"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            </div>
                            <div class="p-4 text-center">
                                <h4
                                    class="font-bold text-sm text-white uppercase mb-1 line-clamp-1 group-hover:text-secondary transition-colors">
                                    {{ $category->nama }}</h4>
                                <p class="text-slate-500 text-[10px] uppercase tracking-widest line-clamp-1">
                                    {{ $category->sub_nama }}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            <div id="mlbb-panel"
                class="tab-content hidden grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3 sm:gap-6"
                data-items-limit="12">
                @foreach ($mlbb as $key => $item)
                    {{-- Mobile View --}}
                    <a href="{{ url('/id/' . $item->kode) }}"
                        class="md:hidden group relative aspect-[3/4] bg-slate-900 rounded-3xl overflow-hidden interactive-card border border-white/5 hover:border-secondary/50 transition-all duration-500 shadow-xl {{ $key >= 12 ? 'hidden extra-item' : '' }}">
                        <img alt="{{ $item->nama }}"
                            src="{{ str_contains($item->thumbnail, 'http') ? $item->thumbnail : (file_exists(public_path($item->thumbnail)) ? asset($item->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                            onerror="this.src='{{ asset('assets/thumbnail/hok.webp') }}'; this.onerror=null;"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent flex flex-col justify-end p-2 sm:p-4">
                            <h4
                                class="font-black text-[10px] sm:text-xs text-white uppercase text-center leading-tight drop-shadow-lg group-hover:text-secondary transition-colors">
                                {{ $item->nama }}</h4>
                        </div>
                    </a>

                    {{-- Desktop View (Classic) --}}
                    <a href="{{ url('/id/' . $item->kode) }}"
                        class="hidden md:flex flex-col group bg-slate-800/30 rounded-3xl overflow-hidden interactive-card border border-white/5 hover:border-secondary/40 transition-all {{ $key >= 12 ? 'hidden extra-item' : '' }}">
                        <div class="aspect-square overflow-hidden relative">
                            <img alt="{{ $item->nama }}"
                                src="{{ str_contains($item->thumbnail, 'http') ? $item->thumbnail : (file_exists(public_path($item->thumbnail)) ? asset($item->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                                onerror="this.src='{{ asset('assets/thumbnail/hok.webp') }}'; this.onerror=null;"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </div>
                        <div class="p-4 text-center">
                            <h4
                                class="font-bold text-sm text-white uppercase mb-1 line-clamp-1 group-hover:text-secondary transition-colors">
                                {{ $item->nama }}</h4>
                            <p class="text-slate-500 text-[10px] uppercase tracking-widest line-clamp-1">
                                {{ $item->sub_nama }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div id="joki-panel" class="tab-content hidden flex-col items-center justify-center py-10 opacity-60">
                <span class="material-symbols-outlined text-6xl mb-4">construction</span>
                <p class="font-bold text-slate-500 uppercase tracking-widest">Coming Soon</p>
            </div>

            <div id="voucher-panel"
                class="tab-content hidden grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3 sm:gap-6"
                data-items-limit="12">
                @foreach ($kategoris as $key => $category)
                    @if ($category->tipe == 'voucher')
                        {{-- Mobile View --}}
                        <a href="{{ url('/id/' . $category->kode) }}"
                            class="md:hidden group relative aspect-[3/4] bg-slate-900 rounded-3xl overflow-hidden interactive-card border border-white/5 hover:border-secondary/50 transition-all duration-500 shadow-xl {{ $key >= 12 ? 'hidden extra-item' : '' }}">
                            <img alt="{{ $category->nama }}"
                                src="{{ str_contains($category->thumbnail, 'http') ? $category->thumbnail : (file_exists(public_path($category->thumbnail)) ? asset($category->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                                onerror="this.src='{{ asset('assets/thumbnail/hok.webp') }}'; this.onerror=null;"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent flex flex-col justify-end p-2 sm:p-4">
                                <h4
                                    class="font-black text-[10px] sm:text-xs text-white uppercase text-center leading-tight drop-shadow-lg group-hover:text-secondary transition-colors">
                                    {{ $category->nama }}</h4>
                            </div>
                        </a>

                        {{-- Desktop View (Classic) --}}
                        <a href="{{ url('/id/' . $category->kode) }}"
                            class="hidden md:flex flex-col group bg-slate-800/30 rounded-3xl overflow-hidden interactive-card border border-white/5 hover:border-secondary/40 transition-all {{ $key >= 12 ? 'hidden extra-item' : '' }}">
                            <div class="aspect-square overflow-hidden relative">
                                <img alt="{{ $category->nama }}"
                                    src="{{ str_contains($category->thumbnail, 'http') ? $category->thumbnail : (file_exists(public_path($category->thumbnail)) ? asset($category->thumbnail) : asset('assets/thumbnail/hok.webp')) }}"
                                    onerror="this.src='{{ asset('assets/thumbnail/hok.webp') }}'; this.onerror=null;"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            </div>
                            <div class="p-4 text-center">
                                <h4
                                    class="font-bold text-sm text-white uppercase mb-1 line-clamp-1 group-hover:text-secondary transition-colors">
                                    {{ $category->nama }}</h4>
                                <p class="text-slate-500 text-[10px] uppercase tracking-widest line-clamp-1">
                                    {{ $category->sub_nama }}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <button id="show-more-btn" onclick="toggleItemsVisibility()"
                    class="px-8 py-3 bg-slate-800/50 hover:bg-slate-700/60 text-slate-300 text-sm font-bold rounded-full border border-white/5 hover:border-white/20 transition-all">
                    Tampilkan Lainnya...
                </button>
            </div>
        </section>


        <!-- Trust Stats Section -->
        <section class="stats-card hidden md:block rounded-[2rem] md:rounded-[2.5rem] py-5 px-4 md:p-12 lg:p-14 overflow-hidden relative">
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg width="100%" height="100%" viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 200 Q 200 100 400 200 T 800 200" stroke="#0EA5E9" fill="transparent" stroke-width="2" />
                </svg>
            </div>
            <div class="relative z-10 flex flex-col items-center gap-4 md:gap-10">
                <div class="text-center space-y-1 md:space-y-2">
                    <p class="text-secondary font-bold tracking-widest uppercase text-xs md:text-sm">Dipercaya oleh ribuan
                        pengguna</p>
                    <h2
                        class="text-2xl md:text-3xl xl:text-4xl font-black text-white leading-tight uppercase tracking-tight">
                        Mustopup dipercaya oleh Para gamers profesional</h2>
                    <p class="text-white/50 font-medium text-sm md:text-lg">Mustopup #1 Tempat Top Up Games Terpercaya No 1
                        Di Indonesia</p>
                </div>

                <div class="grid grid-cols-2 gap-x-2 gap-y-4 md:flex md:flex-wrap items-center justify-center md:gap-16">
                    <div class="flex flex-col md:flex-row items-center gap-4 group/stat text-center md:text-left">
                        <div
                            class="w-10 h-10 md:w-14 md:h-14 bg-slate-900 border border-secondary/30 rounded-xl md:rounded-2xl flex items-center justify-center text-secondary shadow-lg shadow-secondary/10 group-hover/stat:border-secondary group-hover/stat:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-xl md:text-3xl">groups</span>
                        </div>
                        <div>
                            <p class="text-lg md:text-3xl font-black text-white leading-none mb-1">
                                {{ number_format($total_users, 0, '.', '.') }}+</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest whitespace-nowrap">
                                Pengguna</p>
                        </div>
                    </div>

                    <div class="hidden md:block w-px h-10 bg-white/10"></div>

                    <div class="flex flex-col md:flex-row items-center gap-4 group/stat text-center md:text-left">
                        <div
                            class="w-10 h-10 md:w-14 md:h-14 bg-slate-900 border border-secondary/30 rounded-xl md:rounded-2xl flex items-center justify-center text-secondary shadow-lg shadow-secondary/10 group-hover/stat:border-secondary group-hover/stat:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-xl md:text-3xl">sports_esports</span>
                        </div>
                        <div>
                            <p class="text-lg md:text-3xl font-black text-white leading-none mb-1">
                                {{ number_format($total_games, 0, '.', '.') }}+</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest whitespace-nowrap">
                                Games</p>
                        </div>
                    </div>

                    <div class="hidden md:block w-px h-10 bg-white/10"></div>

                    <div class="flex flex-col md:flex-row items-center gap-4 group/stat text-center md:text-left">
                        <div
                            class="w-10 h-10 md:w-14 md:h-14 bg-slate-900 border border-secondary/30 rounded-xl md:rounded-2xl flex items-center justify-center text-secondary shadow-lg shadow-secondary/10 group-hover/stat:border-secondary group-hover/stat:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-xl md:text-3xl">package_2</span>
                        </div>
                        <div>
                            <p class="text-lg md:text-3xl font-black text-white leading-none mb-1">
                                {{ number_format($total_products, 0, '.', '.') }}+</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest whitespace-nowrap">
                                Product</p>
                        </div>
                    </div>

                    <div class="hidden md:block w-px h-10 bg-white/10"></div>

                    <div class="flex flex-col md:flex-row items-center gap-4 group/stat text-center md:text-left">
                        <div
                            class="w-10 h-10 md:w-14 md:h-14 bg-slate-900 border border-secondary/30 rounded-xl md:rounded-2xl flex items-center justify-center text-secondary shadow-lg shadow-secondary/10 group-hover/stat:border-secondary group-hover/stat:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-xl md:text-3xl">payments</span>
                        </div>
                        <div>
                            <p class="text-lg md:text-3xl font-black text-white leading-none mb-1">
                                {{ number_format($total_transactions, 0, '.', '.') }}+</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest whitespace-nowrap">
                                Transaksi</p>
                        </div>
                    </div>
                </div>

                <div class="pt-2 text-center">
                    <a href="#topup-panel"
                        class="inline-flex h-12 md:h-16 px-8 md:px-12 items-center justify-center bg-primary text-background-dark font-black rounded-2xl hover:brightness-110 transition-all shadow-yellow-glow uppercase tracking-widest text-xs md:text-sm whitespace-nowrap">
                        Topup Sekarang
                    </a>
                </div>
            </div>
        </section>

        <section class="mb-20">
            @include('components.user.testimonials')
        </section>
    </main>

    @include('components.user.footer')

    @push('custom_script')
        <script>
            // YouTube Video - Banner Loop Logic
            var player;
            var failSafeTimer;

            // Function to handle manual start if autoplay fails
            window.startVideoManually = function() {
                console.log("Starting video manually...");
                if (player && typeof player.playVideo === 'function') {
                    player.mute();
                    player.playVideo();
                    const overlay = document.getElementById('video-overlay');
                    if (overlay) {
                        overlay.classList.add('hidden');
                        overlay.classList.remove('flex');
                    }
                }
            };

            // Define the global callback for YouTube API
            window.onYouTubeIframeAPIReady = function() {
                console.log("YouTube API Ready Callback Fired");
                initPlayer();
            };

            function initPlayer() {
                console.log("Initializing YouTube Player...");
                const container = document.getElementById('youtube-player');
                if (!container) {
                    console.error("YouTube player container not found!");
                    return;
                }

                player = new YT.Player('youtube-player', {
                    videoId: 'lnVMn3-r8Sg',
                    host: 'https://www.youtube.com',
                    playerVars: {
                        'autoplay': 1,
                        'mute': 1,
                        'controls': 0,
                        'modestbranding': 1,
                        'rel': 0,
                        'showinfo': 0,
                        'iv_load_policy': 3,
                        'autohide': 1,
                        'playsinline': 1,
                        'enablejsapi': 1
                    },
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange,
                        'onError': onPlayerError
                    }
                });

                // Fail-safe: Switch to banner if video hasn't started in 6 seconds
                failSafeTimer = setTimeout(function() {
                    if (!player || typeof player.getPlayerState !== 'function' || (player.getPlayerState() !== YT
                            .PlayerState.PLAYING && player.getPlayerState() !== YT.PlayerState.BUFFERING)) {
                        console.log("YouTube fail-safe triggered. Switching to banner.");
                        switchToBanner();
                    }
                }, 6000);
            }

            // Load the YouTube API script if not already loaded
            if (!window.YT) {
                console.log("Loading YouTube IFrame API script...");
                var tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            } else if (window.YT && window.YT.Player) {
                console.log("YouTube API already loaded, initializing player directly.");
                initPlayer();
            }

            function onPlayerReady(event) {
                console.log("YouTube Player Ready Event");
                event.target.mute();
                event.target.playVideo();

                // Check if video actually starts playing after 3 seconds
                setTimeout(() => {
                    const state = player.getPlayerState();
                    console.log("Player state after 3s:", state);
                    if (state !== YT.PlayerState.PLAYING && state !== YT.PlayerState.BUFFERING) {
                        const overlay = document.getElementById('video-overlay');
                        if (overlay) {
                            overlay.classList.remove('hidden');
                            overlay.classList.add('flex');
                        }
                    }
                }, 3000);
            }

            function onPlayerStateChange(event) {
                console.log("YouTube Player State Change:", event.data);
                if (event.data == YT.PlayerState.PLAYING) {
                    clearTimeout(failSafeTimer);
                    const overlay = document.getElementById('video-overlay');
                    if (overlay) {
                        overlay.classList.add('hidden');
                        overlay.classList.remove('flex');
                    }
                }
                if (event.data == YT.PlayerState.ENDED) {
                    console.log("Video ended, switching to banner.");
                    switchToBanner();
                }
            }

            function onPlayerError(event) {
                console.error("YouTube Player Error:", event.data);
                switchToBanner();
            }

            function switchToBanner() {
                const videoWrapper = document.getElementById('video-wrapper');
                const bannerWrapper = document.getElementById('banner-wrapper');

                if (!videoWrapper || !bannerWrapper) return;

                videoWrapper.classList.add('opacity-0');
                setTimeout(() => {
                    videoWrapper.classList.add('hidden');
                    bannerWrapper.classList.remove('hidden');
                    setTimeout(() => bannerWrapper.classList.remove('opacity-0'), 50);

                    // Start 10s timer to switch back to video
                    setTimeout(switchToVideo, 10000);
                }, 1000);
            }

            function switchToVideo() {
                const videoWrapper = document.getElementById('video-wrapper');
                const bannerWrapper = document.getElementById('banner-wrapper');

                if (!videoWrapper || !bannerWrapper) return;

                bannerWrapper.classList.add('opacity-0');
                setTimeout(() => {
                    bannerWrapper.classList.add('hidden');
                    videoWrapper.classList.remove('hidden');
                    setTimeout(() => videoWrapper.classList.remove('opacity-0'), 50);

                    if (player && typeof player.seekTo === 'function') {
                        player.seekTo(0);
                    }
                    if (player && typeof player.playVideo === 'function') {
                        player.playVideo();
                    }
                }, 1000);
            }

            // Tab switching logic (Dual-mode sync)
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetGroup = this.getAttribute('data-target-group');

                    // Synchronize ALL buttons with the same target-group
                    document.querySelectorAll('.tab-btn').forEach(b => {
                        const bTarget = b.getAttribute('data-target-group');
                        if (bTarget === targetGroup) {
                            b.classList.add('tab-active');
                            b.classList.remove('bg-slate-800/50', 'text-slate-400', 'text-slate-500');
                            if (b.classList.contains('text-slate-500')) {
                                b.classList.add('text-secondary'); // For desktop classic
                            }
                        } else {
                            b.classList.remove('tab-active');
                            if (b.closest('.md\\:hidden')) {
                                // Mobile pill style
                                b.classList.add('bg-slate-800/50', 'text-slate-400');
                            } else {
                                // Desktop classic style
                                b.classList.add('text-slate-500');
                                b.classList.remove('text-secondary');
                            }
                        }
                    });

                    // Switch panels
                    document.querySelectorAll('.tab-content').forEach(p => p.classList.add('hidden'));
                    const targetEl = document.getElementById(targetGroup);
                    if (targetEl) {
                        targetEl.classList.remove('hidden');
                        if (targetGroup === 'joki-panel') {
                            targetEl.classList.add('flex');
                        } else {
                            targetEl.classList.add('grid');
                        }
                    }

                    // Reset Show More button
                    const btnMore = document.getElementById('show-more-btn');
                    if (btnMore) btnMore.textContent = 'Tampilkan Lainnya...';
                });
            });

            // Toggle Show More logic
            function toggleItemsVisibility() {
                const activePanel = document.querySelector('.tab-content:not(.hidden)');
                if (!activePanel) return;

                const hiddenItems = activePanel.querySelectorAll('.extra-item.hidden');
                const btn = document.getElementById('show-more-btn');

                if (hiddenItems.length > 0) {
                    hiddenItems.forEach(item => {
                        item.classList.remove('hidden');
                        item.classList.add('flex'); // Ensuring it shows correctly
                    });
                    btn.textContent = 'Sembunyikan';
                } else {
                    const allExtraItems = activePanel.querySelectorAll('.extra-item');
                    allExtraItems.forEach(item => {
                        item.classList.add('hidden');
                        item.classList.remove('flex');
                    });
                    btn.textContent = 'Tampilkan Lainnya...';
                }
            }
        </script>
    @endpush
@endsection
