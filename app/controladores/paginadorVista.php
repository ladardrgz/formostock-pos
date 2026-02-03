<?php
class Paginador {
    private $pagina_actual; // Guarda la página actual
    private $total_registros; // Guarda el total de registros
    private $registros_por_pagina; // Guarda cuántos registros se mostrarán por página
    private $total_paginas; // Calcula el total de páginas

    // Constructor de la clase, recibe la página actual, total de registros y registros por página
    public function __construct($pagina_actual, $total_registros, $registros_por_pagina) {
        $this->pagina_actual = $pagina_actual;
        $this->total_registros = $total_registros;
        $this->registros_por_pagina = $registros_por_pagina;
        // Calcula el total de páginas, usando ceil para redondear hacia arriba
        $this->total_paginas = ceil($total_registros / $registros_por_pagina);
    }

    // Método que genera el HTML para la paginación
    public function mostrar_paginacion() {
        $pagina_actual = $this->pagina_actual; // Captura la página actual
        $total_paginas = $this->total_paginas; // Captura el total de páginas

        // Obtener el término de búsqueda y el número de registros por página, si están presentes en la solicitud GET
        $search = isset($_GET['search']) ? urlencode($_GET['search']) : '';
        $num_registros = isset($_GET['num_registros']) ? urlencode($_GET['num_registros']) : $this->registros_por_pagina;

        // Inicia la construcción del HTML para la navegación
        $html = '<nav aria-label="Page navigation">';
        $html .= '<ul class="pagination custom-pagination">';

        // Botón de "Anterior"
        if ($pagina_actual > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="?search=' . $search . '&num_registros=' . $num_registros . '&page=' . ($pagina_actual - 1) . '">Anterior</a></li>';
        }

        // Botones de páginas
        for ($i = 1; $i <= $total_paginas; $i++) {
            // Marca la página actual como activa
            $active = ($i == $pagina_actual) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="?search=' . $search . '&num_registros=' . $num_registros . '&page=' . $i . '">' . $i . '</a></li>';
        }

        // Botón de "Siguiente"
        if ($pagina_actual < $total_paginas) {
            $html .= '<li class="page-item"><a class="page-link" href="?search=' . $search . '&num_registros=' . $num_registros . '&page=' . ($pagina_actual + 1) . '">Siguiente</a></li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';

        return $html; // Retorna el HTML generado
    }
}
?>
