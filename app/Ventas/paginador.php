<!-- Paginador -->
<div class="paginador">
    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?search=<?php echo urlencode($buscar); ?>&categoria=<?php echo $categoria_id; ?>&marca=<?php echo $marca_id; ?>&filter_price=<?php echo $filtrar_precio; ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>
