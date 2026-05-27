# 🎨 GUÍA COMPLETA: CÓMO FUNCIONAN LOS DISEÑOS SVG EN ROCKY RECORDS

**Última actualización:** 26 de mayo de 2026

---

## 📐 FUNDAMENTOS SVG - El Lenguaje de Dibujo

SVG (Scalable Vector Graphics) es un "lenguaje de dibujo" dentro de HTML. En lugar de una imagen, defines **formas geométricas** que el navegador dibuja en tiempo real.

### Atributos clave:

| Atributo | Función | Ejemplo |
|----------|---------|---------|
| **viewBox** | Define el "lienzo" (coordenadas del dibujo) | `viewBox="0 0 24 24"` = lienzo de 24x24 |
| **stroke** | Color del contorno de líneas | `stroke="currentColor"` = usa color del texto |
| **fill** | Relleno interior | `fill="none"` = sin relleno; `fill="#C2410C"` = naranja |
| **stroke-width** | Grosor del trazo | `stroke-width="2"` = línea de 2px |

---

## 🔍 ICONO 1: LUPA DE BÚSQUEDA

**📍 Ubicación:** `header.php` línea 55  
**🎯 Función:** Botón dentro del formulario de búsqueda

### Código SVG:
```xml
<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
    <circle cx="11" cy="11" r="8"></circle>      <!-- Lente circular -->
    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>  <!-- Mango diagonal -->
</svg>
```

### Visualización:
```
┌─────────────────────────────┐
│  Lienzo 24x24 (viewBox)     │
│                             │
│     ●●●●●●●●●              │
│   ●         ●               │
│  ●           ●  ← Círculo   │
│  ●   (11,11)  ●   radio 8   │
│  ●           ●              │
│   ●         ●               │
│     ●●●●●●●●●              │
│              ╲              │
│               ╲ ← Línea     │
│                ╲ (mango)    │
└─────────────────────────────┘
```

### CSS asociado (estilos.css línea 97-103):
```css
.boton-busqueda {
    color: #8c847a;              /* Gris por defecto */
    transition: color 0.3s ease; /* Transición suave */
}

.boton-busqueda:hover {
    color: var(--color-naranja); /* Se vuelve naranja al hover */
}
```

### Flujo de uso:
```
Usuario escribe en input
         ↓
Usuario ve lupa gris
         ↓
Hover sobre lupa → se vuelve NARANJA (CSS)
         ↓
Click → envía formulario de búsqueda
         ↓
Redirige a buscar.php
```

---

## ⭐ ICONO 2: LOGO VINILO (EL MÁS COMPLEJO)

**📍 Ubicación:** 
- `header.php` línea 33-41 (Cabecera)
- `footer.php` línea 3-11 (Pie de página)

**🎯 Función:** Logo/marca de la tienda que gira al hacer hover

### Código SVG:
```xml
<svg viewBox="0 0 100 100">
    <!-- Disco principal (negro) -->
    <circle cx="50" cy="50" r="48" fill="#151311" />
    
    <!-- Surcos del vinilo (líneas que se ven en un disco real) -->
    <circle cx="50" cy="50" r="40" fill="none" stroke="#2a2522" stroke-width="1.5" />
    <circle cx="50" cy="50" r="32" fill="none" stroke="#2a2522" stroke-width="1.5" />
    <circle cx="50" cy="50" r="24" fill="none" stroke="#2a2522" stroke-width="1" />
    
    <!-- Etiqueta central (naranja) -->
    <circle cx="50" cy="50" r="16" fill="#C2410C" />
    
    <!-- Centro del disco (punto blanco) -->
    <circle cx="50" cy="50" r="5" fill="#e6dfd5" />
</svg>
```

### Visualización del resultado:
```
                              ◉ ← centro (r=5)
                            ◯◯◯◯◯ ← etiqueta naranja (r=16)
                          ◯◯◯◯◯◯◯◯◯
                        ◯◯◯surcos◯◯◯◯
                      ◯◯◯ (círculos)◯◯◯
                    ◯◯◯◯◯(r=24,32,40)◯◯◯◯
                  ◯◯ DISCO VINILO NEGRO (r=48) ◯◯
                 ◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯◯
                ◯                              ◯
               ◯                                ◯
              ◯                                  ◯
```

### CSS asociado (estilos.css línea 47-49):
```css
.logo-vinilo {
    width: 44px;
    height: 44px;
    transition: transform 0.5s ease;  /* Animación suave */
}

.enlace-logo:hover .logo-vinilo {
    transform: rotate(360deg);  /* GIRA 360° cuando pasas mouse */
}
```

### Desglose de formas geométricas:
- **Circunferencia exterior** (r=48): Borde del disco
- **3 círculos internos** (r=40, 32, 24): Simula los "surcos" del vinilo
- **Círculo naranja** (r=16): Etiqueta típica de vinilos
- **Punto central** (r=5): Centro donde va la aguja

### Flujo de interacción:
```
Usuario entra a página
         ↓
Ve logo vinilo (estático, sin rotación)
         ↓
Mueve mouse sobre logo
         ↓
CSS detecta :hover en .enlace-logo
         ↓
transform: rotate(360deg) se activa
transition: 0.5s ease hace que sea suave
         ↓
LOGO GIRA SUAVEMENTE en 0.5 segundos
         ↓
Mouse se va
         ↓
Vuelve a estado normal (sin rotación)
```

---

## 🎵 ICONO 3: 5 ICONOS DE CARACTERÍSTICAS

**📍 Ubicación:** `index.php` línea 42-88  
**📍 Sección:** Barra debajo de la sección hero  
**🎯 Función:** Mostrar 5 características principales de la tienda

### Primer Icono - Vinilo Exclusivo:
```xml
<svg class="icono-caracteristica" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <circle cx="12" cy="12" r="10"></circle>     <!-- Círculo exterior -->
    <circle cx="12" cy="12" r="3"></circle>      <!-- Círculo pequeño interior -->
</svg>
```

### Los 5 iconos:

| # | Icono | Forma geométrica | Característica |
|---|-------|------------------|-----------------|
| 1️⃣ | **Vinilo** | 2 círculos concéntricos | Ediciones exclusivas |
| 2️⃣ | **Caja 3D** | Líneas en perspectiva (polyline) | Empaque seguro |
| 3️⃣ | **Auriculares** | Formas curvas (path) | Atención personalizada |
| 4️⃣ | **Escudo** | Polígonos (path) | Pagos seguros |
| 5️⃣ | **Nota música** | Línea + dos círculos | Apoya a los artistas |

### CSS asociado (estilos.css línea 362-370):
```css
.icono-caracteristica {
    width: 28px;
    height: 28px;
    color: var(--fondo-carbon);  /* Color del carbón (negro) */
    flex-shrink: 0;              /* No se comprime */
}

.elemento-caracteristica {
    display: flex;               /* Se coloca horizontalmente */
    align-items: center;
    gap: 12px;                   /* Separación entre icono y texto */
}
```

### Ubicación visual en la página:
```
┌───────────────────── HERO SECTION ─────────────────────┐
│  La música suena mejor en físico        [Tocadiscos]    │
│  [EXPLORAR CATÁLOGO ➔]                                 │
└───────────────────────────────────────────────────────┘
              ↓ (justo debajo)
┌───────────────────── BARRA DE CARACTERÍSTICAS ────────────┐
│  [Vinilo] Ediciones exclusivas  | [Caja] Empaque seguro   │
│  [Auriculares] Atención...      | [Escudo] Pagos seguros  │
│  [Nota] Apoya a los artistas                              │
└────────────────────────────────────────────────────────┘
```

---

## 👤 ICONO 4: USUARIO Y CARRITO

**📍 Ubicación:** `header.php` línea 64-80  
**📍 Sección:** Arriba a la derecha en la cabecera  
**🎯 Función:** Enlaces a cuenta y carrito con iconos

### Icono Usuario:
```xml
<svg class="icono-accion" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>  <!-- Cuerpo (trapecio) -->
    <circle cx="12" cy="7" r="4"></circle>                        <!-- Cabeza (círculo) -->
</svg>
```

### Icono Carrito:
```xml
<svg class="icono-accion" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <circle cx="9" cy="21" r="1"></circle>    <!-- Rueda izquierda -->
    <circle cx="20" cy="21" r="1"></circle>   <!-- Rueda derecha -->
    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
</svg>
```

### CSS asociado (estilos.css línea 208-220):
```css
.elemento-accion {
    display: flex;
    flex-direction: column;      /* Icono arriba, texto abajo */
    align-items: center;
    color: #a8a29e;              /* Gris claro */
    transition: color 0.3s ease;
}

.elemento-accion:hover {
    color: var(--texto-claro);   /* Se vuelve blanco al hover */
}

.icono-accion {
    width: 20px;
    height: 20px;
    margin-bottom: 4px;          /* Separación entre icono y etiqueta */
}
```

### Ubicación visual en header:
```
┌─────────────────────────────────────────────────────────────┐
│ HEADER NEGRO                                                │
├──────────────────────────────────────────────────────────┤
│ [Logo] Navegación | [🔍 Búsqueda] | [👤 Usuario] [🛒 Carrito]│
│                                           ↑               ↑  │
│                                      Gris claro      Aparece  │
│                                      Al hover →       cantidad │
│                                      Blanco            en badge│
└─────────────────────────────────────────────────────────────┘
```

### Interactividad:
```
Usuario ve icono gris
         ↓
Hover sobre usuario/carrito
         ↓
CSS: color: var(--texto-claro) se activa
transition: 0.3s ease
         ↓
Icono se vuelve BLANCO suavemente
         ↓
Mouse se va
         ↓
Vuelve a gris
```

---

## 📍 ICONO 5: INSIGNIA FLOTANTE DE ENVÍOS

**📍 Ubicación:** `index.php` línea 36-44  
**📍 Sección:** Sobre la imagen del héroe  
**🎯 Función:** Mostrar que hacen envíos a todo el país

### Código HTML + SVG:
```xml
<div class="insignia-envio">
    <svg class="icono-vinilo-insignia" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/>
        <circle cx="12" cy="12" r="6" fill="none" stroke="currentColor" stroke-width="1.5"/>
        <circle cx="12" cy="12" r="3" fill="currentColor"/>
    </svg>
    <span>Envíos a<br>todo el país</span>
</div>
```

### CSS asociado (estilos.css línea 289-307):
```css
.insignia-envio {
    position: absolute;           /* Flota sobre la imagen */
    bottom: 10px;
    right: 15%;
    background-color: #eae1d4;   /* Fondo crema */
    width: 90px;
    height: 90px;
    border-radius: 50%;           /* Circular */
    border: 2px dashed #bba891;  /* Borde punteado */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    animation: flotar 4s ease-in-out infinite;  /* Animación infinita */
}

@keyframes flotar {
    0%, 100% { transform: translateY(0); }     /* Posición normal */
    50% { transform: translateY(-8px); }       /* Se mueve 8px arriba */
}

.icono-vinilo-insignia {
    width: 22px;
    height: 22px;
    fill: var(--fondo-carbon);   /* Negro */
    margin-bottom: 4px;
}
```

### Visualización:
```
          HÉROE SECTION
    ┌──────────────────────────┐
    │                          │
    │   [Tocadiscos imagen]    │
    │                          │
    │              ╭────────╮  │
    │              │   ◯◯   │  │  ← Insignia flotante
    │              │ Envíos │  │     - Circular
    │              │  a     │  │     - Flota arriba/abajo
    │              │ todo   │  │     - Borde punteado
    │              │        │  │     - Color crema
    │              ╰────────╯  │
    │                          │
    └──────────────────────────┘
```

### Animación detallada:
```
T=0s     T=1s     T=2s     T=3s     T=4s
 ↑       ↗        ↑        ↖        ↑
 │      ◯◯       ◯◯       ◯◯        │
 │     (arriba)  (centro) (arriba)  │
 ↑       ↗        ↑        ↖        ↑
 │       │        │        │        │
 └───────────────────────────────────┘
```

---

## 🎬 CÓMO SE USAN EN EL FLUJO COMPLETO

### Flujo 1: Usuario llega a la página

```
User abre index.php
         ↓
HTML se carga + CSS + JS
         ↓
SVGs se renderizan (son código XML en HTML)
         ↓
CSS aplica estilos (colores, transiciones, animaciones)
         ↓
JS detecta eventos (hover, click, play audio)
         ↓
Usuario ve página con todos los iconos
```

### Flujo 2: Usuario interactúa con Logo

```
User pasa mouse sobre logo vinilo
         ↓
Navegador detecta :hover en .enlace-logo
         ↓
CSS: transform: rotate(360deg) se activa
transition: 0.5s ease hace la animación suave
         ↓
SVG del logo GIRA 360° en 0.5 segundos
         ↓
User quita mouse
         ↓
Animación termina, vuelve a normal
```

### Flujo 3: Reproducción de audio (COMPLEJO)

```
User hace click en PLAY en tarjeta de producto
         ↓
HTML: <audio> element se reproduce
         ↓
JavaScript detecta evento 'play'
         ↓
JS añade clase 'reproduciendo' a la tarjeta
         ↓
CSS:.tarjeta-producto.reproduciendo {
        .disco-soporte {
            animation: rotar-reproduciendo 2.5s infinite;
            ↓ GIRA CONTINUAMENTE
        }
        .contenedor-portada {
            transform: scale(1.04);
            ↓ CRECE 4%
        }
    }
         ↓
Disco GIRA continuamente mientras suena
Portada CRECE (efecto 3D de profundidad)
         ↓
User para el audio (pausa o fin)
         ↓
JS remueve clase 'reproduciendo'
         ↓
Disco y portada vuelven a normal inmediatamente
```

---

## 📊 TABLA COMPLETA: DÓNDE ESTÁ CADA ICONO

| Icono | Archivo | Línea | Elemento | Evento | CSS Class |
|-------|---------|-------|----------|--------|-----------|
| 🔍 Lupa | header.php | 55 | `<svg>` en botón | Click → buscar | `.boton-busqueda` |
| 👤 Usuario | header.php | 66 | `<svg>` en enlace | Hover → naranja | `.elemento-accion` |
| 🛒 Carrito | header.php | 74 | `<svg>` en enlace | Hover → naranja | `.elemento-accion` |
| 📀 Logo | header.php | 33 | `<svg>` en logo | Hover → rotar | `.logo-vinilo` |
| 📀 Logo | footer.php | 3 | `<svg>` en logo | Estático | `.logo-vinilo` |
| ⭐ 5 Caract. | index.php | 42-88 | 5 `<svg>` | Estático | `.icono-caracteristica` |
| 📍 Insignia | index.php | 39 | `<svg>` pequeño | Animation | `.icono-vinilo-insignia` |

---

## 🔧 RESUMEN TÉCNICO: CICLO DE VIDA DE UN ICONO

```javascript
// Cómo funciona el ciclo de vida completo:

1. FASE HTML CARGA
   └─→ <svg viewBox="..."><circle cx="12"/></svg> 
   └─→ SVG es parseado como XML
   └─→ Navegador crea elementos DOM

2. FASE CSS APLICA
   └─→ .icono-accion { width: 20px; color: #8c847a; }
   └─→ transform, stroke, fill se aplican al SVG
   └─→ transition, animation se registran

3. FASE JAVASCRIPT ESCUCHA
   └─→ document.addEventListener('play', ...) 
   └─→ classList.add('reproduciendo')
   └─→ classList.remove('reproduciendo')

4. FASE TRANSICIONES/ANIMACIONES
   └─→ transition: 0.3s → cambios suaves
   └─→ @keyframes flotar → animación infinita
   └─→ animation: rotar-reproduciendo → rotación continua

5. FASE USUARIO INTERACTÚA
   └─→ Hover/Click/Play
   └─→ Eventos cambian clases CSS
   └─→ CSS anima el SVG
   └─→ Usuario ve efecto visual
```

---

## 💡 PUNTOS CLAVE A RECORDAR

✅ **SVG es XML dibujado** - define formas, no imágenes  
✅ **viewBox define el lienzo** - coordenadas internas  
✅ **stroke = línea, fill = relleno** - conceptos básicos  
✅ **CSS controla la visualización** - colores, tamaños, animaciones  
✅ **Eventos JS cambian clases** - que activan CSS  
✅ **Las transiciones hacen suave** - los cambios de estado  
✅ **@keyframes animan continuamente** - movimiento infinito  

---

## 🔗 REFERENCIAS DENTRO DEL PROYECTO

- **CSS Completo:** `css/estilos.css`
- **Header:** `header.php` (todos los iconos del top)
- **Página Principal:** `index.php` (hero, características, catálogo)
- **Pie:** `footer.php` (logo repetido)
- **Búsqueda:** `buscar.php` (usa los mismos iconos)

---

**Nota:** Este documento cubre los 5 iconos SVG principales. Hay más SVGs en tarjetas de producto (discos giratorios) pero tienen la misma lógica.

