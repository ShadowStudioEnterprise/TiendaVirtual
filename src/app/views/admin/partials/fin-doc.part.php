					</div>
				</div>
				<!-- /.row -->
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="<?=$basePath;?>/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=$basePath;?>/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?=$basePath;?>/js/plugins/morris/raphael.min.js"></script>
    <script src="<?=$basePath;?>/js/plugins/morris/morris.min.js"></script>
    <script src="<?=$basePath;?>/js/plugins/morris/morris-data.js"></script>
    <script>
        function checkDelete(){
            //Siempre que una acción no se pueda deshacer hay que pedir confirmación al usuario
            if (confirm("¿Seguro que desea borrar este elemento? "))
                return true;
            else
                return false;
        }
    </script>
</body>

</html>
