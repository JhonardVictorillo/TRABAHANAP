<!-- <div id="posts" class="section" style="display: none;">
    <h2>All Posts</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Freelancer</th>
                <th>Sub-services</th>
                <th>Post-image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
            @foreach($post->freelancer->categories as $category)
                <td>{{ $category->name }}</td>
                @endforeach
                <td>{{ $post->freelancer->firstname }} {{ $post->freelancer->lastname }}</td>
                <td>
                @php
                        $subServices = is_string($post->sub_services) ? json_decode($post->sub_services, true) : $post->sub_services;
                    @endphp

                    @if(is_array($subServices))
                    
                 <ul>
                 @foreach ($subServices as $subService)
                <li>{{ $subService }}</li>
                @endforeach
                </ul>
                
                @else
                
                <p>No sub-services available</p>
                
                @endif
                </td>
                <td>
                    @php
                $postPictures = json_decode($post->post_picture);
                @endphp
                @foreach ($postPictures as $imagePath)
                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Post Image" style="width: 50px; height: 50px;">
                @endforeach
                    <td>
                    @if($post->approved)
                        <span style="color: green;">Approved</span>
                    @else
                        <span style="color: red;">Pending</span>
                    @endif
                </td>
                <td>
                    @if(!$post->approved)
                        <form action="{{ route('admin.approvePost', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="approve-btn">Approve</button>
                        </form>
                        <form action="{{ route('admin.rejectPost', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="reject-btn">Reject</button>
                        </form>
                    @else
                        <span>Approved</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</main> -->  

 