

            <h4 class="heading">{{$slot}}</h4>
            <div class="panel panel-lang">
                <div class="panel-body">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="{{$lang->locale}}-{{$name}}">
                            @if (!isset($type))
                            <input type="text" class="input-field" name="{{$lang->locale}}[{{$name}}]"
                                placeholder="{{$placeholder ?? ''}}" 
                                {{(isset($required) && $required === true) ? "required" : ""}}
                                @if(isset($value))
                                value="{{$from->{$value} ?? ''}}"
                                @endif
                                >
                            @else
                                @if($type == "richtext")
                                <div class="text-editor">
                                    <textarea class="trumboedit-p" name="{{$lang->locale}}[{{$name}}]" 
                                        {{(isset($required) && $required === true) ? "required" : ""}}
                                        >@if(isset($value)){{$from->{$value} ?? ''}}@endif</textarea>
                                </div>
                                @endif
                                @if($type == "textarea")
                                <div class="text-editor">
                                    <textarea class="input-field" name="{{$lang->locale}}[{{$name}}]" 
                                        placeholder="{{$placeholder ?? ''}}" 
                                        {{(isset($required) && $required === true) ? "required" : ""}}
                                        >@if(isset($value)){{$from->{$value} ?? ''}}@endif</textarea>
                                </div>
                                @endif
                                @if($type == "tags")
                                    <ul id="{{$lang->locale}}-tags-{{$name}}" class="myTags">
                                        @if(isset($value))
                                            @if(!empty($from->{$value}))
                                                @foreach ($from->{$value} as $element)
                                                    <li>{{  $element }}</li>
                                                @endforeach
                                            @endif
                                        @endif
                                    </ul>
                                    <script>
                                        setInterval(function() {
                                            $("#{{$lang->locale}}-tags-{{$name}}").tagit({
                                            fieldName: "{{$lang->locale}}[{{$name}}][]",
                                            allowSpaces: true 
                                        });
                                        },1000);
                                    </script>
                                @endif
                            @endif
                        </div>

                        @foreach($locales as $loc)
                            @if($loc->locale === $lang->locale)
                                @continue
                            @endif
                            <div role="tabpanel" class="tab-pane" id="{{$loc->locale}}-{{$name}}">
                                @if (!isset($type))
                                <input type="text" class="input-field" name="{{$loc->locale}}[{{$name}}]"
                                    placeholder="{{$placeholder ?? ''}}" 
                                    @if(isset($value))
                                    value="{{$from->translate($loc->locale)->{$value} ?? ''}}"
                                    @endif
                                    >
                                @else
                                    @if($type == "richtext")
                                    <div class="text-editor">
                                        <textarea class="trumboedit-p" name="{{$loc->locale}}[{{$name}}]"
                                            >@if(isset($value)){{$from->translate($loc->locale)->{$value} ?? ''}}@endif</textarea>
                                    </div>
                                    @endif
                                    @if($type == "textarea")
                                    <div class="text-editor">
                                        <textarea class="input-field" name="{{$loc->locale}}[{{$name}}]" 
                                            placeholder="{{$placeholder ?? ''}}" 
                                            >@if(isset($value)){{$from->translate($loc->locale)->{$value} ?? ''}}@endif</textarea>
                                    </div>
                                    @endif
                                    @if($type == "tags")
                                        <ul id="{{$loc->locale}}-tags-{{$name}}" class="myTags">
                                            @if(isset($value))
                                                @if(!empty($from->translate($loc->locale)->{$value}))
                                                    @php
                                                        $tags = explode(',', $from->translate($loc->locale)->{$value})
                                                    @endphp
                                                    @foreach ($tags as $element)
                                                        <li>{{  $element }}</li>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </ul>
                                        <script>
                                            setInterval(function() {
                                                $("#{{$loc->locale}}-tags-{{$name}}").tagit({
                                                fieldName: "{{$loc->locale}}[{{$name}}][]",
                                                allowSpaces: true 
                                            });
                                            },1000);
                                        </script>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="panel-footer">
                    <ul class="nav nav-pills" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#{{$lang->locale}}-{{$name}}" class="active" aria-controls="{{$lang->locale}}-{{$name}}"
                                role="tab" data-toggle="tab">
                                {{$lang->language}}
                            </a>
                        </li>
                        @foreach ($locales as $loc)
                            @if($loc->locale === $lang->locale)
                                @continue
                            @endif
                            <li role="presentation">
                                <a href="#{{$loc->locale}}-{{$name}}" aria-controls="{{$loc->locale}}-{{$name}}"
                                    role="tab" data-toggle="tab">
                                    {{$loc->language}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
