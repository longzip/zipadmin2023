<template>
    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              
            </div>

            <h3 class="profile-username text-center">{{lead.title}}  {{lead.last_name}}</h3>

            <p class="text-muted text-center">{{lead.phone}}</p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Email</b> <a class="float-right">{{lead.email}}</a>
              </li>
              <li class="list-group-item">
                <b>Note</b> <a class="float-right">{{lead.address}}</a>
              </li>
            </ul>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab">Timeline</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                	<template v-for="d in contact">
						<li class="time-label">
		                    <span class="bg-danger">
		                      {{d.created_at | myDateTime}}
		                    </span>
						</li>
						<li>
		                    <i class="fa fa-envelope bg-primary"></i>

		                    <div class="timeline-item">
								<span class="time"><i class="fa fa-clock-o"></i> {{ d.created_at | myDateN }} </span>

								<!-- <h3 class="timeline-header" v-if="d.subject_type === NoiThatZip\Lead\Models\Lead'">
									KH15
								</h3>
								<h3 class="timeline-header" v-else>
									KHTN
								</h3> -->
								<div class="timeline-body">
								{{ d.description }}
								</div>
								<!-- <div class="timeline-footer">
								<a href="#" class="btn btn-primary btn-sm">Read more</a>
								<a href="#" class="btn btn-danger btn-sm">Delete</a>
								</div> -->
		                    </div>
						</li>
						<li>
	                    	<i class="fa fa-clock-o bg-gray"></i>
						</li>
                	</template>
                </ul>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
      </div>
      <!-- /.col -->
    </div>
</template>
<script>
export default {
    //props: ['id'],
    data() {
        return {
            contact: {},
            lead: {},
        }
    },
    methods: {
        loadcontact() {   
            axios.get("/api/showinfocustum?id=" + this.$route.query.id + '&idContact=' + this.$route.query.idContact)
                .then(response => {
                    this.contact = response.data.kh;
                    this.lead = response.data.lead;
                });
        },
    },
    created() {
        this.loadcontact();
    }
}
</script>
