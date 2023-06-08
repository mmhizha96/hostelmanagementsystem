import { TestBed } from '@angular/core/testing';

import { HostelService } from './hostel.service';

describe('HostelService', () => {
  let service: HostelService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HostelService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
