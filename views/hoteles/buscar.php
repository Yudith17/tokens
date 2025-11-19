<?php include '../header.php'; ?>

<div class="main-container">
    <?php include '../sidebar.php'; ?>
    
    <main class="main-content">
        <div class="content-section">
            <h2>Buscar Hoteles</h2>
            
            <form id="search-hotels-form" class="search-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" id="nombre-hotel" name="nombre">
                    </div>
                    <div class="form-group">
                        <label>Ciudad:</label>
                        <input type="text" id="ciudad-hotel" name="ciudad">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            <div id="hoteles-results" class="results-table">
                <!-- Los resultados se cargarán aquí via JavaScript -->
            </div>
        </div>
    </main>
</div>

<script src="../js/app.js"></script>
</body>
</html>