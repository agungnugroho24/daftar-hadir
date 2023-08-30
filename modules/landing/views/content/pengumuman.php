<div class="pt-21 pt-sm-24 pt-md-25 pt-lg-26 pb-lg-0 pb-md-9 pb-11 position-relative z-index-1 overflow-hidden">
    <div class="container position-relative">
        <div class="row position-relative">
        <!-- area content -->
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Judul</th>
                </tr>
                </thead>
                    <tbody>
                    <?php if ($news) { 
                        $i=0;
                        foreach ($news as $value) { $i++;?>   
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$value['created_date']?></td>
                                <td><a href="<?=base_url('jpt/detail/'.$value['news_slug'])?>"><?=$value['news_title']?></a></td>
                            </tr>               
                    <?php }} ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>
<div class="margin-footer"></div>