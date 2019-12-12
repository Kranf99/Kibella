<?php
/*
JSON_SQL_Bridge 1.0
Copyright 2016 Frank Vanden berghen
All Right reserved.

JSON_SQL_Bridge is not a free software. The JSON_SQL_Bridge software is NOT licensed under the "Apache License". 
If you are interested in distributing, reselling, modifying, contibuting or in general creating 
any derivative work from JSON_SQL_Bridge, please contact Frank Vanden Berghen at frank@timi.eu.
*/
namespace kibella; function __kibana_4___kibanaqueryvalidator__validate_query() { $l55="{\"valid\":true,\"_shards\":{\"total\":1,\"successful\":1,\"failed\":0},\"explanations\":[{\"index\":\".kibana-4\",\"valid\":true,\"explanation\":\"ConstantScore(ConstantScore(+_type:__kibanaQueryValidator))\"}]}"; O36($l55); } function __kibana_4__mapping___field__source() { $l55="{\".kibana-4\":{\"mappings\":{\"index-pattern\":{\"_source\":{\"full_name\":\"_source\",\"mapping\":{}}},\"search\":{\"_source\":{\"full_name\":\"_source\",\"mapping\":{}}},\"visualization\":{\"_source\":{\"full_name\":\"_source\",\"mapping\":{}}},\"config\":{\"_source\":{\"full_name\":\"_source\",\"mapping\":{}}},\"dashboard\":{\"_source\":{\"full_name\":\"_source\",\"mapping\":{}}}}}}"; O36($l55); } function __kibana_4__refresh() { $l55="{\"_shards\":{\"total\":2,\"successful\":1,\"failed\":0}}"; O36($l55); } function __kibana_4__settings() { $l55="{\"acknowledge\":true}"; O36($l55); } function __kibana_4_config_4_1_2_es_2_0() { $l55="{\"_index\":\".kibana-4\",\"_type\":\"config\",\"_id\":\"4.1.2\",\"_version\":1,\"_shards\":{\"total\":2,\"successful\":1,\"failed\":0}}"; O36($l55); } function __kibana_4_config() { $l55="{\"_index\":\".kibana-4\",\"_type\":\"config\",\"_id\":\"4.1.2\",\"_version\":1,\"_shards\":{\"total\":2,\"successful\":1,\"failed\":0}}"; O36($l55); } function __kibana_4() { $l55="{\n\t  \".kibana-4\" : {\n\t    \"aliases\" : { },\n\t    \"mappings\" : {\n\t      \"config\" : {\n\t        \"properties\" : {\n\t          \"buildNum\" : {\n\t            \"type\" : \"long\"\n\t          }\n\t        }\n\t      }\n\t    },\n\t    \"settings\" : {\n\t      \"index\" : {\n\t        \"creation_date\" : \"1456837727490\",\n\t        \"uuid\" : \"KyQTolBsQKKDHGaS1FPSLQ\",\n\t        \"number_of_replicas\" : \"1\",\n\t        \"number_of_shards\" : \"1\",\n\t        \"version\" : {\n\t          \"created\" : \"2020099\"\n\t        }\n\t      }\n\t    },\n\t    \"warmers\" : { }\n\t  }\n\t}"; O36($l55); } function __cluster_health__kibana_4() { $l55="{\"cluster_name\":\"elasticsearch\",\"status\":\"yellow\",\"timed_out\":false,\"number_of_nodes\":1,\"number_of_data_nodes\":1,\"active_primary_shards\":1,\"active_shards\":1,\"relocating_shards\":0,\"initializing_shards\":0,\"unassigned_shards\":1,\"delayed_unassigned_shards\":0,\"number_of_pending_tasks\":0,\"number_of_in_flight_fetch\":0,\"task_max_waiting_in_queue_millis\":0,\"active_shards_percent_as_number\":50.0}"; O36($l55); } function __nodes() { $l55="{\n\t  \"cluster_name\" : \"elasticsearch\",\n\t  \"nodes\" : {\n\t    \"9X39ibGRSziEvcgaY0h21w\" : {\n\t      \"name\" : \"Awesome Android\",\n\t      \"transport_address\" : \"10.0.2.15:9300\",\n\t      \"host\" : \"10.0.2.15\",\n\t      \"ip\" : \"10.0.2.15\",\n\t      \"version\" : \"2.2.0\",\n\t      \"build\" : \"8ff36d1\",\n\t      \"http_address\" : \"10.0.2.15:9200\",\n\t      \"settings\" : {\n\t        \"client\" : {\n\t          \"type\" : \"node\"\n\t        },\n\t        \"http\" : {\n\t          \"cors\" : {\n\t            \"enabled\" : \"true\",\n\t            \"allow-origin\" : \"*\"\n\t          }\n\t        },\n\t        \"name\" : \"Awesome Android\",\n\t        \"pidfile\" : \"/var/run/elasticsearch/elasticsearch.pid\",\n\t        \"path\" : {\n\t          \"data\" : \"/var/lib/elasticsearch\",\n\t          \"home\" : \"/usr/share/elasticsearch\",\n\t          \"conf\" : \"/etc/elasticsearch\",\n\t          \"logs\" : \"/var/log/elasticsearch\"\n\t        },\n\t        \"config\" : {\n\t          \"ignore_system_properties\" : \"true\"\n\t        },\n\t        \"cluster\" : {\n\t          \"name\" : \"elasticsearch\"\n\t        },\n\t        \"network\" : {\n\t          \"host\" : \"0.0.0.0\"\n\t        },\n\t        \"foreground\" : \"false\"\n\t      },\n\t      \"os\" : {\n\t        \"refresh_interval_in_millis\" : 1000,\n\t        \"name\" : \"Linux\",\n\t        \"arch\" : \"amd64\",\n\t        \"version\" : \"3.2.0-23-generic\",\n\t        \"available_processors\" : 2,\n\t        \"allocated_processors\" : 2\n\t      },\n\t      \"process\" : {\n\t        \"refresh_interval_in_millis\" : 1000,\n\t        \"id\" : 2056,\n\t        \"mlockall\" : false\n\t      },\n\t      \"jvm\" : {\n\t        \"pid\" : 2056,\n\t        \"version\" : \"1.7.0_95\",\n\t        \"vm_name\" : \"OpenJDK 64-Bit Server VM\",\n\t        \"vm_version\" : \"24.95-b01\",\n\t        \"vm_vendor\" : \"Oracle Corporation\",\n\t        \"start_time_in_millis\" : 1456758145723,\n\t        \"mem\" : {\n\t          \"heap_init_in_bytes\" : 268435456,\n\t          \"heap_max_in_bytes\" : 1056309248,\n\t          \"non_heap_init_in_bytes\" : 24313856,\n\t          \"non_heap_max_in_bytes\" : 224395264,\n\t          \"direct_max_in_bytes\" : 1056309248\n\t        },\n\t        \"gc_collectors\" : [ \"ParNew\", \"ConcurrentMarkSweep\" ],\n\t        \"memory_pools\" : [ \"Code Cache\", \"Par Eden Space\", \"Par Survivor Space\", \"CMS Old Gen\", \"CMS Perm Gen\" ],\n\t        \"using_compressed_ordinary_object_pointers\" : \"true\"\n\t      },\n\t      \"thread_pool\" : {\n\t        \"generic\" : {\n\t          \"type\" : \"cached\",\n\t          \"keep_alive\" : \"30s\",\n\t          \"queue_size\" : -1\n\t        },\n\t        \"index\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 2,\n\t          \"max\" : 2,\n\t          \"queue_size\" : 200\n\t        },\n\t        \"fetch_shard_store\" : {\n\t          \"type\" : \"scaling\",\n\t          \"min\" : 1,\n\t          \"max\" : 4,\n\t          \"keep_alive\" : \"5m\",\n\t          \"queue_size\" : -1\n\t        },\n\t        \"get\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 2,\n\t          \"max\" : 2,\n\t          \"queue_size\" : 1000\n\t        },\n\t        \"snapshot\" : {\n\t          \"type\" : \"scaling\",\n\t          \"min\" : 1,\n\t          \"max\" : 1,\n\t          \"keep_alive\" : \"5m\",\n\t          \"queue_size\" : -1\n\t        },\n\t        \"force_merge\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 1,\n\t          \"max\" : 1,\n\t          \"queue_size\" : -1\n\t        },\n\t        \"suggest\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 2,\n\t          \"max\" : 2,\n\t          \"queue_size\" : 1000\n\t        },\n\t        \"bulk\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 2,\n\t          \"max\" : 2,\n\t          \"queue_size\" : 50\n\t        },\n\t        \"warmer\" : {\n\t          \"type\" : \"scaling\",\n\t          \"min\" : 1,\n\t          \"max\" : 1,\n\t          \"keep_alive\" : \"5m\",\n\t          \"queue_size\" : -1\n\t        },\n\t        \"flush\" : {\n\t          \"type\" : \"scaling\",\n\t          \"min\" : 1,\n\t          \"max\" : 1,\n\t          \"keep_alive\" : \"5m\",\n\t          \"queue_size\" : -1\n\t        },\n\t        \"search\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 4,\n\t          \"max\" : 4,\n\t          \"queue_size\" : 1000\n\t        },\n\t        \"fetch_shard_started\" : {\n\t          \"type\" : \"scaling\",\n\t          \"min\" : 1,\n\t          \"max\" : 4,\n\t          \"keep_alive\" : \"5m\",\n\t          \"queue_size\" : -1\n\t        },\n\t        \"listener\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 1,\n\t          \"max\" : 1,\n\t          \"queue_size\" : -1\n\t        },\n\t        \"percolate\" : {\n\t          \"type\" : \"fixed\",\n\t          \"min\" : 2,\n\t          \"max\" : 2,\n\t          \"queue_size\" : 1000\n\t        },\n\t        \"refresh\" : {\n\t          \"type\" : \"scaling\",\n\t          \"min\" : 1,\n\t          \"max\" : 1,\n\t          \"keep_alive\" : \"5m\",\n\t          \"queue_size\" : -1\n\t        },\n\t        \"management\" : {\n\t          \"type\" : \"scaling\",\n\t          \"min\" : 1,\n\t          \"max\" : 5,\n\t          \"keep_alive\" : \"5m\",\n\t          \"queue_size\" : -1\n\t        }\n\t      },\n\t      \"transport\" : {\n\t        \"bound_address\" : [ \"[::]:9300\" ],\n\t        \"publish_address\" : \"10.0.2.15:9300\",\n\t        \"profiles\" : { }\n\t      },\n\t      \"http\" : {\n\t        \"bound_address\" : [ \"[::]:9200\" ],\n\t        \"publish_address\" : \"10.0.2.15:9200\",\n\t        \"max_content_length_in_bytes\" : 104857600\n\t      },\n\t      \"plugins\" : [ ],\n\t      \"modules\" : [ {\n\t        \"name\" : \"lang-expression\",\n\t        \"version\" : \"2.2.0\",\n\t        \"description\" : \"Lucene expressions integration for Elasticsearch\",\n\t        \"jvm\" : true,\n\t        \"classname\" : \"org.elasticsearch.script.expression.ExpressionPlugin\",\n\t        \"isolated\" : true,\n\t        \"site\" : false\n\t      }, {\n\t        \"name\" : \"lang-groovy\",\n\t        \"version\" : \"2.2.0\",\n\t        \"description\" : \"Groovy scripting integration for Elasticsearch\",\n\t        \"jvm\" : true,\n\t        \"classname\" : \"org.elasticsearch.script.groovy.GroovyPlugin\",\n\t        \"isolated\" : true,\n\t        \"site\" : false\n\t      } ]\n\t    }\n\t  }\n\t}"; O36($l55); } function _() { $l55="{\n\t  \"name\" : \"Awesome Android\",\n\t  \"cluster_name\" : \"elasticsearch\",\n\t  \"version\" : {\n\t    \"number\" : \"2.2.0\",\n\t    \"build_hash\" : \"8ff36d139e16f8720f2947ef62c8167a888992fe\",\n\t    \"build_timestamp\" : \"2016-01-27T13:32:39Z\",\n\t    \"build_snapshot\" : false,\n\t    \"lucene_version\" : \"5.4.1\"\n\t  },\n\t  \"tagline\" : \"You Know, for Search\"\n\t}"; O36($l55); }