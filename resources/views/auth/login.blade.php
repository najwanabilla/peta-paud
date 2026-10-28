<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Peta PAUD Balikpapan</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --blue: #0875df;
            --dark: #103c78;
            --muted: #6d87a8;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background: #edf7ff;
        }

        .page {
            display: grid;
            grid-template-columns: 1.12fr .88fr;
            min-height: 100vh;
        }

        /* BRAND */
        .welcome {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            padding: 30px 6% 20px;
            background:
                radial-gradient(ellipse at 15% 48%, #ffffff85, transparent 45%),
                linear-gradient(180deg, #a9ddff 0%, #eaf8ff 60%, #dff4dc 100%);
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 10;
        }

        .logo {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            background: linear-gradient(145deg, #1187ed, #0755a7);
            color: #ffdf54;
            border: 3px solid white;
            border-radius: 50% 50% 50% 15%;
            transform: rotate(-45deg);
            display: grid;
            place-items: center;
            box-shadow: 0 6px 16px #1458a526;
        }

        .logo i {
            transform: rotate(45deg);
            font-size: 23px;
        }

        .brand-name {
            font-size: 19px;
            font-weight: 800;
            line-height: 1.25;
            color: #06499c;
        }

        /* WELCOME TEXT */
        .intro {
            position: relative;
            z-index: 8;
            margin-top: 24px;
        }

        .tag {
            display: inline-block;
            background: #ffffffd9;
            color: #0875c9;
            border: 1px solid white;
            border-radius: 30px;
            padding: 8px 13px;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .intro h1 {
            font-size: clamp(27px, 2.8vw, 40px);
            line-height: 1.23;
            font-weight: 800;
            margin-bottom: 12px;
            color: #123f76;
        }

        .intro h1 span {
            color: #0875df;
        }

        .intro p {
            max-width: 490px;
            font-size: 12px;
            line-height: 1.9;
            color: #547698;
        }

        .features {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 11px;
            border-radius: 13px;
            background: #ffffffdc;
            border: 1px solid white;
            font-size: 10px;
            box-shadow: 0 5px 15px #315f8510;
        }

        .feature i {
            font-size: 19px;
            color: var(--blue);
        }

        .feature b {
            display: block;
            font-size: 10px;
            color: #104783;
        }

        .feature small {
            display: block;
            color: #7891ac;
            font-size: 8px;
        }

        /* ILUSTRASI TAMAN */
        .scene {
            flex: 1;
            min-height: 390px;
            position: relative;
            margin: 5px -7% -20px;
            overflow: hidden;
            isolation: isolate;
        }

        .sun {
            position: absolute;
            top: 15px;
            right: 13%;
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #ffe58a;
            box-shadow: 0 0 0 12px #ffe58a35, 0 0 35px #ffdc7c35;
        }

        .cloud {
            position: absolute;
            top: 35px;
            left: 8%;
            width: 75px;
            height: 22px;
            border-radius: 30px;
            background: white;
            box-shadow: 20px -13px 0 -2px white, 40px -5px 0 1px white;
        }

        .cloud-2 {
            top: 100px;
            left: 67%;
            transform: scale(.75);
            opacity: .9;
        }

        .cloud-3 {
            top: 145px;
            left: 35%;
            transform: scale(.55);
            opacity: .75;
        }

        .birds {
            position: absolute;
            top: 78px;
            left: 32%;
        }

        .bird {
            position: absolute;
            width: 14px;
            height: 9px;
            border-top: 2px solid #7292a8;
            border-radius: 50%;
        }

        .bird-1 {
            left: 0;
            transform: rotate(-15deg);
        }

        .bird-2 {
            left: 22px;
            top: 9px;
            transform: rotate(15deg);
        }

        .bird-3 {
            left: 43px;
            top: -7px;
            transform: rotate(-12deg);
        }

        /* POHON */
        .tree {
            position: absolute;
            bottom: 66px;
            width: 17px;
            height: 115px;
            background: linear-gradient(90deg, #95603f, #b87a4d);
            border-radius: 7px;
            z-index: 1;
        }

        .tree::before {
            content: "";
            position: absolute;
            width: 80px;
            height: 85px;
            left: -31px;
            top: -54px;
            border-radius: 50%;
            background: #6fc978;
            box-shadow:
                -14px 17px 0 -5px #91d986,
                17px 20px 0 -5px #51b465,
                0 -10px 0 -9px #a2e69a;
        }

        .tree.left {
            left: 2%;
        }

        .tree.right {
            right: 2%;
            transform: scale(.85);
        }

        /* SEMAK */
        .bush {
            position: absolute;
            bottom: 75px;
            width: 75px;
            height: 35px;
            background: #72ca77;
            border-radius: 50%;
            z-index: 2;
            box-shadow:
                18px 8px 0 -4px #51b766,
                -15px 10px 0 -5px #8bd782;
        }

        .bush.left {
            left: 0;
        }

        .bush.right {
            right: 0;
        }

        /* GEDUNG SEKOLAH */
        .school {
            position: absolute;
            bottom: 66px;
            left: 15%;
            width: 70%;
            z-index: 3;
        }

        .roof {
            height: 62px;
            background: linear-gradient(180deg, #ff9679, #f87559);
            border: 4px solid #e76a50;
            clip-path: polygon(15% 0, 85% 0, 100% 100%, 0 100%);
            position: relative;
        }

        .roof::after {
            content: "";
            position: absolute;
            top: 13px;
            left: 20%;
            width: 60%;
            height: 3px;
            background: #ffb09a;
            box-shadow: 0 13px #ffb09a, 0 26px #ffb09a;
        }

        .roof-top {
            position: absolute;
            top: -27px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 30px;
            background: #ff8264;
            clip-path: polygon(50% 0, 100% 100%, 0 100%);
        }

        .building {
            min-height: 115px;
            background: linear-gradient(180deg, #fff4ce, #ffe8a8);
            border: 5px solid #f2ca78;
            border-top: 4px solid #f6cf81;
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            gap: 7px;
            padding: 10px 12px 0;
            position: relative;
        }

        .building::before {
            content: "TAMAN KANAK-KANAK";
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            color: #a76535;
            font-size: 7px;
            font-weight: 800;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        .window {
            position: relative;
            flex: 1;
            max-width: 48px;
            height: 48px;
            border: 4px solid white;
            background: linear-gradient(135deg, #9be7ff, #50bde9);
            box-shadow: inset 0 0 0 2px #51a9d4;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .window::after {
            content: "";
            position: absolute;
            left: 47%;
            height: 100%;
            width: 3px;
            background: white;
        }

        .window::before {
            content: "";
            position: absolute;
            top: 47%;
            width: 100%;
            height: 3px;
            background: white;
        }

        .door {
            width: 43px;
            height: 66px;
            flex-shrink: 0;
            background: linear-gradient(90deg, #cb8955, #e7b27a);
            border: 4px solid white;
            border-bottom: none;
            border-radius: 23px 23px 0 0;
            position: relative;
        }

        .door::after {
            content: "";
            position: absolute;
            right: 5px;
            top: 35px;
            width: 5px;
            height: 5px;
            background: #fff1bb;
            border-radius: 50%;
        }

        .school-sign {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 4;
            background: white;
            border-radius: 8px;
            padding: 5px 14px;
            font-size: 15px;
            font-weight: 800;
            box-shadow: 0 4px 10px #00000015;
            border: 2px solid #e0ebf5;
            white-space: nowrap;
            letter-spacing: 1px;
        }

        .school-sign .p { color: #ef4444; }
        .school-sign .a { color: #f59e0b; }
        .school-sign .u { color: #10b981; }
        .school-sign .d { color: #3b82f6; }
        .school-sign .c { color: #8b5cf6; }

        .flagpole {
            position: absolute;
            top: -78px;
            right: 15%;
            height: 84px;
            width: 3px;
            background: #a3aab3;
            z-index: 5;
        }

        .flag {
            position: absolute;
            width: 27px;
            height: 17px;
            background: linear-gradient(#f44d49 50%, white 50%);
            left: 3px;
            top: 0;
            clip-path: polygon(0 0, 100% 10%, 84% 100%, 0 90%);
        }

        /* RUMPUT DAN JALAN */
        .grass {
            position: absolute;
            z-index: 1;
            bottom: 0;
            left: -10%;
            width: 120%;
            height: 105px;
            background: linear-gradient(180deg, #a6e68d, #72c96c);
            border-radius: 50% 50% 0 0;
        }

        .path {
            position: absolute;
            z-index: 2;
            bottom: -18px;
            left: 29%;
            width: 42%;
            height: 125px;
            background: linear-gradient(90deg, #f5d9af, #ffedcf, #f5d9af);
            clip-path: polygon(35% 0, 65% 0, 100% 100%, 0 100%);
        }

        /* AYUNAN */
        .swing-container {
            position: absolute;
            bottom: 38px;
            left: 6%;
            z-index: 4;
            width: 80px;
            height: 95px;
        }

        .swing-leg-left,
        .swing-leg-right {
            position: absolute;
            bottom: 0;
            width: 6px;
            height: 90px;
            background: #f3a51a;
            border-radius: 3px;
        }

        .swing-leg-left { left: 10px; }
        .swing-leg-right { right: 10px; }

        .swing-topbar {
            position: absolute;
            top: 0;
            left: 5px;
            width: 70px;
            height: 8px;
            background: #f3a51a;
            border-radius: 4px;
            box-shadow: 0 3px 0 #d98a0b;
        }

        .swing-rope,
        .swing-rope2 {
            position: absolute;
            top: 8px;
            width: 3px;
            height: 45px;
            background: #7c8fa4;
        }

        .swing-rope { left: 25px; }
        .swing-rope2 { right: 25px; }

        .swing-seat {
            position: absolute;
            top: 53px;
            left: 18px;
            width: 38px;
            height: 8px;
            background: #ed5555;
            border-radius: 4px;
            box-shadow: 0 3px 0 #b33636;
        }

        /* JUNGKAT-JUNGKIT */
        .seesaw {
            position: absolute;
            z-index: 4;
            bottom: 35px;
            left: 24%;
            width: 80px;
            height: 40px;
        }

        .seesaw-base {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 12px solid transparent;
            border-right: 12px solid transparent;
            border-bottom: 25px solid #f59e0b;
            z-index: 2;
        }

        .seesaw-board {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 80px;
            height: 8px;
            background: #ef4444;
            border-radius: 4px;
            transform: rotate(-10deg);
            z-index: 3;
            box-shadow: 0 2px 0 #b91c1c;
        }

        .seesaw-handle {
            position: absolute;
            width: 3px;
            height: 12px;
            background: #64748b;
            bottom: 26px;
        }

        .seesaw-handle.left { left: 12px; }
        .seesaw-handle.right { right: 12px; }

        /* TAMAN BUNGA PENGGANTI LAPANGAN */
        .garden {
            position: absolute;
            bottom: 27px;
            right: 6%;
            z-index: 6;
            width: 88px;
            height: 100px;
        }

        .garden-stem {
            position: absolute;
            bottom: 27px;
            left: 42px;
            width: 4px;
            height: 48px;
            border-radius: 5px;
            background: #329b57;
            transform: rotate(5deg);
        }

        .garden-leaf {
            position: absolute;
            width: 23px;
            height: 12px;
            background: #55bd70;
            border-radius: 100% 0 100% 0;
        }

        .garden-leaf.left {
            bottom: 43px;
            left: 20px;
            transform: rotate(15deg);
        }

        .garden-leaf.right {
            bottom: 54px;
            left: 42px;
            transform: rotate(75deg);
        }

        .garden-flower {
            position: absolute;
            bottom: 69px;
            left: 31px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fb8bc0;
            box-shadow:
                0 -9px 0 -2px #fb8bc0,
                0 9px 0 -2px #fb8bc0,
                9px 0 0 -2px #fb8bc0,
                -9px 0 0 -2px #fb8bc0;
        }

        .garden-flower::after {
            content: "";
            position: absolute;
            width: 8px;
            height: 8px;
            left: 6px;
            top: 6px;
            border-radius: 50%;
            background: #ffdf59;
        }

        .garden-pot {
            position: absolute;
            bottom: 0;
            left: 22px;
            width: 42px;
            height: 29px;
            background: linear-gradient(135deg, #ffad75, #e87950);
            clip-path: polygon(5% 0, 95% 0, 80% 100%, 20% 100%);
            border-radius: 3px;
        }

        .garden-pot::before {
            content: "";
            position: absolute;
            top: 0;
            width: 100%;
            height: 6px;
            background: #c96745;
        }

        /* BOLA MAINAN */
        .ball {
            position: absolute;
            z-index: 8;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            box-shadow: inset -4px -4px 0 #00000013, 0 3px 5px #00000015;
        }

        .ball::after {
            content: "";
            position: absolute;
            top: 4px;
            left: 5px;
            width: 7px;
            height: 4px;
            border-radius: 50%;
            background: #ffffffb8;
            transform: rotate(-35deg);
        }

        .ball-1 {
            bottom: 35px;
            left: 18%;
            background: #ff7777;
        }

        .ball-2 {
            bottom: 45px;
            right: 20%;
            width: 18px;
            height: 18px;
            background: #f8c84b;
        }

        .ball-3 {
            bottom: 25px;
            right: 12%;
            width: 15px;
            height: 15px;
            background: #66b9fa;
        }

        /* BUNGA KECIL */
        .flower {
            position: absolute;
            z-index: 7;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f472b6;
            box-shadow:
                7px 0 #f472b6,
                4px -6px #f472b6,
                4px 6px #f472b6,
                -3px 1px #f472b6;
        }

        .flower::after {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            width: 5px;
            height: 5px;
            background: #ffdf56;
            border-radius: 50%;
        }

        .flower-1 { bottom: 20px; left: 4%; }
        .flower-2 { bottom: 55px; left: 12%; }
        .flower-3 { bottom: 25px; right: 3%; }
        .flower-4 { bottom: 65px; right: 16%; }

        /* KUPU-KUPU */
        .butterfly {
            position: absolute;
            z-index: 7;
            width: 24px;
            height: 24px;
        }

        .b-1 { bottom: 125px; left: 32%; }
        .b-2 { bottom: 160px; right: 32%; transform: scale(.8); }

        /* ANAK-ANAK */
        .children-image-container {
            position: absolute;
            z-index: 9;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            pointer-events: none;
            gap: 0;
        }

        .children-image-container img {
            display: block;
            width: 42%;
            max-width: 180px;
            height: auto;
            max-height: 210px;
            object-fit: contain;
            object-position: bottom;
            filter: drop-shadow(0 5px 5px #244a2920);
        }

        .children-image-container img + img {
            margin-left: -22px;
        }

        .front-grass {
            position: absolute;
            bottom: 0;
            z-index: 10;
            width: 100%;
            height: 12px;
            background: linear-gradient(180deg, #86d678, #65bf60);
            clip-path: polygon(
                0 65%, 3% 20%, 5% 75%, 8% 25%, 11% 70%,
                14% 20%, 18% 80%, 22% 35%, 26% 70%, 30% 20%,
                34% 75%, 39% 25%, 43% 75%, 47% 20%, 52% 70%,
                57% 25%, 62% 80%, 67% 20%, 72% 70%, 77% 30%,
                82% 75%, 87% 20%, 92% 75%, 96% 25%, 100% 70%,
                100% 100%, 0 100%
            );
            pointer-events: none;
        }

        .copyright {
            position: relative;
            z-index: 11;
            color: #65829e;
            font-size: 9px;
            padding-top: 10px;
        }

        /* LOGIN FORM */
        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 35px 7%;
            background:
                radial-gradient(ellipse at 90% 10%, #d4edff, transparent 40%),
                linear-gradient(150deg, #e1f3ff, #fbfdff 65%, #e2f6e8);
        }

        .card {
            width: 100%;
            max-width: 455px;
            background: white;
            border: 1px solid #e0ebf5;
            border-radius: 24px;
            padding: 35px 32px;
            box-shadow: 0 20px 60px #2a6da51c;
        }

        .card-brand {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 13px;
            margin-bottom: 25px;
        }

        .card-brand .logo {
            width: 50px;
            height: 50px;
        }

        .card-brand h2 {
            font-size: 20px;
            font-weight: 800;
            line-height: 1.3;
            color: #06499c;
        }

        .heading {
            text-align: center;
            margin-bottom: 27px;
        }

        .heading h3 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .heading p {
            font-size: 12px;
            line-height: 1.8;
            color: var(--muted);
        }

        .group {
            margin-bottom: 19px;
        }

        .group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .input {
            height: 53px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 14px;
            border: 1px solid #d2dfed;
            border-radius: 11px;
            transition: .2s;
        }

        .input:focus-within {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px #0875df12;
        }

        .input > i {
            width: 20px;
            text-align: center;
            color: #7188a8;
        }

        .input input {
            flex: 1;
            width: 100%;
            min-width: 0;
            border: 0;
            outline: 0;
            font-family: inherit;
            font-size: 12px;
            color: var(--dark);
            background: transparent;
        }

        .input input::placeholder {
            color: #9aabc1;
        }

        .eye {
            border: none;
            background: transparent;
            cursor: pointer;
            color: #7188a8;
            padding: 5px;
        }

        .login-btn {
            width: 100%;
            height: 53px;
            border: 0;
            border-radius: 11px;
            margin-top: 5px;
            background: linear-gradient(100deg, #0875df, #0964c5);
            color: white;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 7px 18px #0875df25;
            transition: .2s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px #0875df35;
        }

        .login-btn i {
            margin-right: 8px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 23px 0;
            color: #8b9cb4;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            background: #e0e8f1;
            flex: 1;
        }

        .user-btn {
            display: block;
            width: 100%;
            padding: 14px;
            border: 1px solid #b7cbe3;
            border-radius: 11px;
            text-align: center;
            text-decoration: none;
            color: #0870d5;
            background: white;
            font-size: 12px;
            font-weight: 600;
            transition: .2s;
        }

        .user-btn:hover {
            background: #f0f8ff;
            border-color: #0870d5;
        }

        .user-btn i {
            margin-right: 8px;
        }

        .security {
            text-align: center;
            margin-top: 23px;
            font-size: 10px;
            color: #8799b1;
        }

        .security i {
            margin-right: 5px;
        }

        /* RESPONSIVE */
        @media (max-width: 1000px) {
            .page {
                grid-template-columns: 1fr;
            }

            .welcome {
                min-height: 760px;
                padding: 30px 7% 20px;
            }

            .intro h1 {
                font-size: 35px;
            }

            .scene {
                min-height: 360px;
            }

            .form-side {
                min-height: auto;
                padding: 45px 20px;
            }

            .card {
                max-width: 480px;
            }

            .children-image-container img {
                max-width: 170px;
            }
        }

        @media (max-width: 500px) {
            .welcome {
                min-height: 690px;
                padding: 24px 6% 15px;
            }

            .brand-name {
                font-size: 18px;
            }

            .intro {
                margin-top: 19px;
            }

            .intro h1 {
                font-size: 28px;
            }

            .intro p {
                font-size: 11px;
            }

            .feature {
                padding: 8px;
                gap: 6px;
            }

            .feature i {
                font-size: 15px;
            }

            .feature b {
                font-size: 9px;
            }

            .feature small {
                font-size: 7px;
            }

            .scene {
                min-height: 300px;
                margin-top: 15px;
            }

            .school {
                bottom: 49px;
                left: 8%;
                width: 84%;
            }

            .building {
                min-height: 95px;
                padding: 10px 4px 0;
            }

            .roof {
                height: 50px;
            }

            .window {
                height: 40px;
                border-width: 3px;
            }

            .door {
                width: 34px;
                height: 55px;
            }

            .school-sign {
                top: -9px;
                font-size: 12px;
            }

            .building::before {
                font-size: 6px;
            }

            .tree {
                transform: scale(.7);
                transform-origin: bottom;
            }

            .tree.right {
                transform: scale(.65);
                transform-origin: bottom;
            }

            .swing-container {
                transform: scale(.65);
                transform-origin: bottom left;
                left: 3%;
            }

            .seesaw {
                transform: scale(.65);
                transform-origin: bottom left;
                left: 12%;
            }

            .garden {
                right: 2%;
                transform: scale(.75);
                transform-origin: bottom right;
            }

            .children-image-container img {
                width: 44%;
                max-width: 135px;
                max-height: 155px;
            }

            .children-image-container img + img {
                margin-left: -15px;
            }

            .card {
                padding: 29px 21px;
                border-radius: 20px;
            }

            .heading h3 {
                font-size: 25px;
            }

            .copyright {
                font-size: 8px;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .butterfly {
                animation: flutter 3s ease-in-out infinite alternate;
            }

            .b-2 {
                animation-delay: 1s;
            }

            .cloud-2 {
                animation: drift 9s ease-in-out infinite alternate;
            }

            @keyframes flutter {
                from { transform: translateY(0) rotate(-5deg); }
                to { transform: translateY(-7px) rotate(8deg); }
            }

            @keyframes drift {
                from { translate: -5px 0; }
                to { translate: 8px 0; }
            }
        }
    </style>
</head>

<body>
<div class="page">

    <!-- BAGIAN KIRI -->
    <section class="welcome">
        <div class="brand">
            <div class="logo">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="brand-name">
                Peta PAUD<br>Balikpapan
            </div>
        </div>

        <div class="intro">
            <div class="tag">
                <i class="fa-solid fa-graduation-cap"></i>
                SISTEM INFORMASI PENDIDIKAN
            </div>

            <h1>
                Selamat Datang di<br>
                <span>Peta PAUD Balikpapan</span>
            </h1>

            <p>
                Temukan informasi lengkap PAUD di Kota Balikpapan.
                Mudah, cepat, dan terpercaya untuk membantu menemukan
                pendidikan terbaik bagi anak.
            </p>

            <div class="features">
                <div class="feature">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <b>Peta Interaktif</b>
                        <small>Lokasi PAUD</small>
                    </div>
                </div>

                <div class="feature">
                    <i class="fa-solid fa-school"></i>
                    <div>
                        <b>Data PAUD</b>
                        <small>Informasi lengkap</small>
                    </div>
                </div>

                <div class="feature">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div>
                        <b>Akurat & Terpercaya</b>
                        <small>Data terperbarui</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ILUSTRASI TAMAN BERMAIN -->
        <div class="scene">
            <div class="sun"></div>
            <div class="cloud"></div>
            <div class="cloud cloud-2"></div>
            <div class="cloud cloud-3"></div>

            <div class="birds">
                <div class="bird bird-1"></div>
                <div class="bird bird-2"></div>
                <div class="bird bird-3"></div>
            </div>

            <div class="tree left"></div>
            <div class="tree right"></div>
            <div class="bush left"></div>
            <div class="bush right"></div>

            <!-- AYUNAN -->
            <div class="swing-container">
                <div class="swing-leg-left"></div>
                <div class="swing-leg-right"></div>
                <div class="swing-topbar"></div>
                <div class="swing-rope"></div>
                <div class="swing-rope2"></div>
                <div class="swing-seat"></div>
            </div>

            <!-- JUNGKAT-JUNGKIT -->
            <div class="seesaw">
                <div class="seesaw-base"></div>
                <div class="seesaw-board"></div>
                <div class="seesaw-handle left"></div>
                <div class="seesaw-handle right"></div>
            </div>

            <!-- TAMAN BUNGA PENGGANTI LAPANGAN -->
            <div class="garden" aria-hidden="true">
                <div class="garden-stem"></div>
                <div class="garden-leaf left"></div>
                <div class="garden-leaf right"></div>
                <div class="garden-flower"></div>
                <div class="garden-pot"></div>
            </div>

            <!-- GEDUNG PAUD -->
            <div class="school">
                <div class="flagpole">
                    <div class="flag"></div>
                </div>

                <div class="roof"></div>
                <div class="roof-top"></div>

                <div class="school-sign">
                    <span class="p">P</span><span class="a">A</span><span class="u">U</span><span class="d">D</span>&#x20;
                    <span class="c">C</span><span class="a">E</span><span class="u">R</span><span class="p">I</span><span class="d">A</span>
                </div>

                <div class="building">
                    <div class="window"></div>
                    <div class="window"></div>
                    <div class="door"></div>
                    <div class="window"></div>
                    <div class="window"></div>
                </div>
            </div>

            <!-- TAMAN DAN JALAN -->
            <div class="grass"></div>
            <div class="path"></div>

            <!-- BOLA MAINAN -->
            <div class="ball ball-1"></div>
            <div class="ball ball-2"></div>
            <div class="ball ball-3"></div>

            <!-- KUPU-KUPU -->
            <div class="butterfly b-1">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C12 12 8 6 4 8C1 9.5 3 14 7 14C4 14 2 18 5 19.5C8 21 12 16 12 16"
                          fill="#F472B6" stroke="#DB2777" stroke-width="1" stroke-linejoin="round"/>
                    <path d="M12 12C12 12 16 6 20 8C23 9.5 21 14 17 14C20 14 22 18 19 19.5C16 21 12 16 12 16"
                          fill="#F472B6" stroke="#DB2777" stroke-width="1" stroke-linejoin="round"/>
                    <circle cx="7" cy="10" r="1.5" fill="#FFF"/>
                    <circle cx="17" cy="10" r="1.5" fill="#FFF"/>
                    <path d="M12 10V16" stroke="#4B5563" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M11 8L9 5M13 8L15 5" stroke="#4B5563" stroke-width="1" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="butterfly b-2">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C12 12 8 6 4 8C1 9.5 3 14 7 14C4 14 2 18 5 19.5C8 21 12 16 12 16"
                          fill="#FCD34D" stroke="#D97706" stroke-width="1" stroke-linejoin="round"/>
                    <path d="M12 12C12 12 16 6 20 8C23 9.5 21 14 17 14C20 14 22 18 19 19.5C16 21 12 16 12 16"
                          fill="#FCD34D" stroke="#D97706" stroke-width="1" stroke-linejoin="round"/>
                    <circle cx="7" cy="10" r="1.5" fill="#FFF"/>
                    <circle cx="17" cy="10" r="1.5" fill="#FFF"/>
                    <path d="M12 10V16" stroke="#4B5563" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M11 8L9 5M13 8L15 5" stroke="#4B5563" stroke-width="1" stroke-linecap="round"/>
                </svg>
            </div>

            <!-- BUNGA -->
            <div class="flower flower-1"></div>
            <div class="flower flower-2"></div>
            <div class="flower flower-3"></div>
            <div class="flower flower-4"></div>

            <!-- GAMBAR ANAK-ANAK -->
            <div class="children-image-container">
                <img src="{{ asset('images/gambar-anak-paud.png') }}" alt="Anak-anak PAUD bermain bersama">
                <img src="{{ asset('images/gambar-anak-paud1.png') }}" alt="Teman bermain di taman PAUD">
            </div>

            <div class="front-grass"></div>
        </div>

        <div class="copyright">
            © 2026 Peta PAUD Balikpapan. Semua hak dilindungi.
        </div>
    </section>

    <!-- BAGIAN KANAN: LOGIN -->
    <section class="form-side">
        <div class="card">
            <div class="card-brand">
                <div class="logo">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h2>Peta PAUD<br>Balikpapan</h2>
            </div>

            <div class="heading">
                <h3>Login Admin</h3>
                <p>
                    Masuk untuk mengakses dashboard dan<br>
                    fasilitas Peta PAUD Balikpapan.
                </p>
            </div>

            {{-- Menampilkan pesan error jika login gagal --}}
            @if ($errors->any())
                <div style="background-color: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; border: 1px solid #f87171;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form id="loginForm" action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="group">
                    <label for="email">Username / Email</label>
                    <div class="input">
                        <i class="fa-regular fa-user"></i>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            placeholder="Masukkan email"
                            autocomplete="username"
                            required
                            value="{{ old('email') }}"
                        >
                    </div>
                </div>

                <div class="group">
                    <label for="password">Password</label>
                    <div class="input">
                        <i class="fa-solid fa-lock"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="eye"
                            id="togglePassword"
                            aria-label="Tampilkan password"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </button>
            </form>

            <div class="divider">atau</div>

            <a href="{{ url('/') }}" class="user-btn">
                <i class="fa-regular fa-user"></i>
                Kembali ke Halaman Utama
            </a>

            <div class="security">
                <i class="fa-solid fa-lock"></i>
                Akses terbatas untuk administrator terdaftar
            </div>
        </div>
    </section>
</div>

<script>
    const password = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');

    toggle.addEventListener('click', function () {
        const visible = password.type === 'password';

        password.type = visible ? 'text' : 'password';

        this.innerHTML = visible
            ? '<i class="fa-regular fa-eye-slash"></i>'
            : '<i class="fa-regular fa-eye"></i>';

        this.setAttribute(
            'aria-label',
            visible ? 'Sembunyikan password' : 'Tampilkan password'
        );
    });
</script>

</body>
</html>